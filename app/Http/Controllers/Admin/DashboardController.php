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

        // Donation Stats
        $donations = [
            'zakat_fitrah' => Claim::sum('zakat_fitrah_amount'),
            'infaq' => Claim::sum('infaq_amount'),
            'sodaqoh' => Claim::sum('sodaqoh_amount'),
        ];
        $donations['total'] = $donations['zakat_fitrah'] + $donations['infaq'] + $donations['sodaqoh'];

        // Chart Data (Last 30 Days)
        $endDate = now();
        $startDate = now()->subDays(29);
        
        $dailyStats = Claim::selectRaw('DATE(created_at) as date, 
                SUM(zakat_fitrah_amount) as zakat, 
                SUM(infaq_amount) as infaq, 
                SUM(sodaqoh_amount) as sodaqoh')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Prepare chart structure with zero-filling
        $chartLabels = [];
        $chartData = [
            'zakat' => [],
            'infaq' => [],
            'sodaqoh' => []
        ];

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $chartLabels[] = $startDate->copy()->addDays($i)->format('d M');
            
            $dayStat = $dailyStats[$date] ?? null;
            $chartData['zakat'][] = $dayStat ? $dayStat->zakat : 0;
            $chartData['infaq'][] = $dayStat ? $dayStat->infaq : 0;
            $chartData['sodaqoh'][] = $dayStat ? $dayStat->sodaqoh : 0;
        }

        return view('admin.dashboard', compact('kpis', 'recentClaims', 'recentRedemptions', 'donations', 'chartLabels', 'chartData'));
    }
}
