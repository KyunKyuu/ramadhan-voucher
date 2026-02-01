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
     * @return Claim
     * @throws ValidationException
     */
    public function processClaim(string $code, int $picId, string $name, string $email): Claim
    {
        return DB::transaction(function () use ($code, $picId, $name, $email) {
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
            }

            // Generate unique public token
            $publicToken = $this->generateUniquePublicToken();

            // Create claim record
            $claim = Claim::create([
                'initial_voucher_id' => $voucher->id,
                'name' => $name,
                'email' => $email,
                'public_token' => $publicToken,
            ]);

            // Update voucher status to CLAIMED
            $voucher->update([
                'status' => 'CLAIMED',
                'claimed_at' => now(),
            ]);

            // Generate merchant vouchers
            $this->merchantVoucherGenerator->generateForClaim($voucher);

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
            'merchantVouchers.merchant.offer',
        ])->where('public_token', $token)->first();
    }
}
