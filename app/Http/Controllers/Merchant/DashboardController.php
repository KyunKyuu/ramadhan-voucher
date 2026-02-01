<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantVoucher;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show merchant dashboard.
     */
    public function index()
    {
        $merchantId = Auth::user()->merchant_id;

        // KPIs
        $kpis = [
            'total_redeemed' => MerchantVoucher::where('merchant_id', $merchantId)
                ->where('status', 'REDEEMED')
                ->count(),
            
            'redeemed_today' => MerchantVoucher::where('merchant_id', $merchantId)
                ->where('status', 'REDEEMED')
                ->whereDate('redeemed_at', today())
                ->count(),
            
            'active_vouchers' => MerchantVoucher::where('merchant_id', $merchantId)
                ->where('status', 'ACTIVE')
                ->count(),
            
            'total_vouchers' => MerchantVoucher::where('merchant_id', $merchantId)
                ->count(),
        ];

        // Recent redemptions
        $recentRedemptions = MerchantVoucher::with(['initialVoucher.claim', 'redeemedBy'])
            ->where('merchant_id', $merchantId)
            ->where('status', 'REDEEMED')
            ->latest('redeemed_at')
            ->take(10)
            ->get();

        return view('merchant.dashboard', compact('kpis', 'recentRedemptions'));
    }
}
