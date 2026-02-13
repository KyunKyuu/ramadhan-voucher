<?php

namespace App\Services;

use App\Models\Claim;
use App\Models\InitialVoucher;
use App\Support\CodeGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClaimService
{
    protected $merchantVoucherGenerator;

    public function __construct(MerchantVoucherGenerator $merchantVoucherGenerator)
    {
        $this->merchantVoucherGenerator = $merchantVoucherGenerator;
    }

    /**
     * Validate if a voucher code can be claimed.
     *
     * @param string $code
     * @return InitialVoucher
     * @throws ValidationException
     */
    public function validateVoucherForClaim(string $code): InitialVoucher
    {
        $voucher = InitialVoucher::with('pic')
            ->where('code', $code)
            ->first();

        if (!$voucher) {
            throw ValidationException::withMessages([
                'code' => 'Voucher tidak ditemukan.',
            ]);
        }

        if ($voucher->status !== 'ASSIGNED') {
            $message = match ($voucher->status) {
                'UNASSIGNED' => 'Voucher belum di-assign ke PIC.',
                'CLAIMED' => 'Voucher sudah pernah diklaim.',
                'VOID' => 'Voucher sudah tidak berlaku.',
                default => 'Voucher tidak dapat diklaim.',
            };

            throw ValidationException::withMessages([
                'code' => $message,
            ]);
        }

        if (!$voucher->assigned_pic_id) {
            throw ValidationException::withMessages([
                'code' => 'Voucher belum di-assign ke PIC.',
            ]);
        }

        return $voucher;
    }

    /**
     * Process claim atomically.
     *
     * @param string $code
     * @param int $picId
     * @param string $name
     * @param string $email
     * @param string|null $phone
     * @param float $zakatFitrahAmount
     * @param float $infaqAmount
     * @param float $sodaqohAmount
     * @return Claim
     * @throws ValidationException
     */
    public function processClaim(
        string $code,
        int $picId,
        string $name,
        string $email,
        ?string $phone = null,
        float $zakatFitrahAmount = 0,
        float $infaqAmount = 0,
        float $sodaqohAmount = 0
    ): Claim
    {
        return DB::transaction(function () use ($code, $picId, $name, $email, $phone, $zakatFitrahAmount, $infaqAmount, $sodaqohAmount) {
            // Lock the voucher row for update
            $voucher = InitialVoucher::where('code', $code)
                ->lockForUpdate()
                ->first();

            // Re-validate after lock (prevent race condition)
            if (!$voucher || $voucher->status !== 'ASSIGNED') {
                throw ValidationException::withMessages([
                    'code' => 'Voucher tidak dapat diklaim. Mungkin sudah diklaim oleh orang lain.',
                ]);
            }

            // Validate PIC matches voucher
            if ($voucher->assigned_pic_id != $picId) {
                throw ValidationException::withMessages([
                    'pic_id' => 'Voucher ini tidak di-assign ke PIC yang Anda pilih. Silakan pilih PIC yang benar.',
            ]);

            $minClaimAmount = (float) config('app.min_claim_amount', 35000);
            $totalAmount = $zakatFitrahAmount + $infaqAmount + $sodaqohAmount;

            // Generate merchant vouchers only if minimum total is met
            if ($totalAmount >= $minClaimAmount) {
                $this->merchantVoucherGenerator->generateForClaim($voucher);
            }

            return $claim;
        });
    }

    /**
     * Generate unique public token.
     *
     * @return string
     */
    protected function generateUniquePublicToken(): string
    {
        do {
            $token = CodeGenerator::makeToken(32);
        } while (Claim::where('public_token', $token)->exists());

        return $token;
    }

    /**
     * Get claim by public token.
     *
     * @param string $token
     * @return Claim|null
     */
    public function getClaimByToken(string $token): ?Claim
    {
        return Claim::with([
            'initialVoucher.pic',
            'merchantVouchers.merchant.offer.images',
        ])->where('public_token', $token)->first();
    }
}
