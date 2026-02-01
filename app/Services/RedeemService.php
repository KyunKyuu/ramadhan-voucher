<?php

namespace App\Services;

use App\Models\MerchantVoucher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RedeemService
{
    /**
     * Validate voucher for redemption.
     *
     * @param string $code
     * @param int $merchantId
     * @return MerchantVoucher
     * @throws ValidationException
     */
    public function validateVoucher(string $code, int $merchantId): MerchantVoucher
    {
        $voucher = MerchantVoucher::with([
            'merchant',
            'initialVoucher.claim',
            'initialVoucher.pic'
        ])
        ->where('code', $code)
        ->first();

        if (!$voucher) {
            throw ValidationException::withMessages([
                'code' => 'Voucher tidak ditemukan.',
            ]);
        }

        // Check if voucher belongs to this merchant
        if ($voucher->merchant_id !== $merchantId) {
            throw ValidationException::withMessages([
                'code' => 'Voucher ini bukan untuk merchant Anda.',
            ]);
        }

        // Check if voucher is active
        if ($voucher->status !== 'ACTIVE') {
            $message = $voucher->status === 'REDEEMED'
                ? 'Voucher sudah pernah diredeem pada ' . $voucher->redeemed_at->format('d M Y H:i')
                : 'Voucher tidak dapat digunakan.';

            throw ValidationException::withMessages([
                'code' => $message,
            ]);
        }

        return $voucher;
    }

    /**
     * Redeem voucher atomically.
     *
     * @param string $code
     * @param int $merchantId
     * @param int $merchantUserId
     * @return MerchantVoucher
     * @throws ValidationException
     */
    public function redeemVoucher(string $code, int $merchantId, int $merchantUserId): MerchantVoucher
    {
        return DB::transaction(function () use ($code, $merchantId, $merchantUserId) {
            // Lock the voucher row for update
            $voucher = MerchantVoucher::where('code', $code)
                ->where('merchant_id', $merchantId)
                ->lockForUpdate()
                ->first();

            // Re-validate after lock (prevent race condition)
            if (!$voucher || $voucher->status !== 'ACTIVE') {
                throw ValidationException::withMessages([
                    'code' => 'Voucher tidak dapat diredeem. Mungkin sudah diredeem oleh kasir lain.',
                ]);
            }

            // Update voucher status
            $voucher->update([
                'status' => 'REDEEMED',
                'redeemed_at' => now(),
                'redeemed_by_merchant_user_id' => $merchantUserId,
            ]);

            // Reload relationships
            $voucher->load([
                'merchant',
                'initialVoucher.claim',
                'initialVoucher.pic',
                'redeemedBy'
            ]);

            return $voucher;
        });
    }

    /**
     * Get voucher details for display.
     *
     * @param string $code
     * @param int $merchantId
     * @return array|null
     */
    public function getVoucherDetails(string $code, int $merchantId): ?array
    {
        try {
            $voucher = $this->validateVoucher($code, $merchantId);
            
            return [
                'valid' => true,
                'voucher' => $voucher,
                'user_name' => $voucher->initialVoucher->claim->name ?? 'N/A',
                'user_email' => $voucher->initialVoucher->claim->email ?? 'N/A',
                'pic_name' => $voucher->initialVoucher->pic->name ?? 'N/A',
                'offer' => $voucher->merchant->offer,
            ];
        } catch (ValidationException $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
