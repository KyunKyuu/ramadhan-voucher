<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InitialVoucher;
use App\Models\Claim;
use App\Models\MerchantVoucher;
use App\Models\Pic;
use App\Models\Merchant;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // KPI Calculations
        $kpis = [
            'total_vouchers_generated' => InitialVoucher::count(),
            'total_assigned' => InitialVoucher::where('status', 'ASSIGNED')->count(),
            'total_claimed' => InitialVoucher::where('status', 'CLAIMED')->count(),
            'total_claims' => Claim::count(),
            'total_merchant_vouchers' => MerchantVoucher::count(),
            'total_redeemed' => MerchantVoucher::where('status', 'REDEEMED')->count(),
            'total_pics' => Pic::count(),
            'active_pics' => Pic::where('is_active', true)->count(),
            'total_merchants' => Merchant::count(),
            'active_merchants' => Merchant::where('is_active', true)->count(),
        ];

        // Calculate conversion rates
        $kpis['claim_rate'] = $kpis['total_assigned'] > 0 
            ? round(($kpis['total_claimed'] / $kpis['total_assigned']) * 100, 1)
            : 0;

        $kpis['redeem_rate'] = $kpis['total_merchant_vouchers'] > 0
            ? round(($kpis['total_redeemed'] / $kpis['total_merchant_vouchers']) * 100, 1)
            : 0;

        // Recent claims (last 5)
        $recentClaims = Claim::with('initialVoucher.pic')
            ->latest()
            ->take(5)
            ->get();

        // Recent redemptions (last 5)
        $recentRedemptions = MerchantVoucher::with(['merchant', 'redeemedBy'])
            ->where('status', 'REDEEMED')
            ->latest('redeemed_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('kpis', 'recentClaims', 'recentRedemptions'));
    }
}
