<?php

namespace App\Services;

use App\Models\InitialVoucher;
use App\Models\Merchant;
use App\Models\MerchantVoucher;
use App\Support\CodeGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MerchantVoucherGenerator
{
    /**
     * Generate merchant vouchers for a claim.
     *
     * @param InitialVoucher $initialVoucher
     * @return Collection
     */
    public function generateForClaim(InitialVoucher $initialVoucher): Collection
    {
        return DB::transaction(function () use ($initialVoucher) {
            // Get all active merchants
            $merchants = Merchant::where('is_active', true)->get();

            $merchantVouchers = [];

            foreach ($merchants as $merchant) {
                $merchantVouchers[] = MerchantVoucher::create([
                    'initial_voucher_id' => $initialVoucher->id,
                    'merchant_id' => $merchant->id,
                    'code' => $this->generateUniqueCode(),
                    'status' => 'ACTIVE',
                ]);
            }

            return collect($merchantVouchers);
        });
    }

    /**
     * Generate a unique code for merchant voucher.
     *
     * @return string
     */
    protected function generateUniqueCode(): string
    {
        do {
            $code = CodeGenerator::make(14);
        } while (MerchantVoucher::where('code', $code)->exists());

        return $code;
    }
}
