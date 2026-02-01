<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\ClaimService;

class VoucherListController extends Controller
{
    protected $claimService;

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }

    /**
     * Show the voucher list.
     *
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function show(string $token)
    {
        $claim = $this->claimService->getClaimByToken($token);

        if (!$claim) {
            abort(404, 'Voucher tidak ditemukan.');
        }

        return view('public.vouchers', [
            'claim' => $claim,
            'merchantVouchers' => $claim->merchantVouchers,
        ]);
    }
}
