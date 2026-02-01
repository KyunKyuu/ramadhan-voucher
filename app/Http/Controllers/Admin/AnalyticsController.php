<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InitialVoucher;
use App\Models\Claim;
use App\Models\MerchantVoucher;
use App\Models\Pic;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show analytics dashboard.
     */
    public function index(Request $request)
    {
        // Date filter (default: last 7 days)
        $dateFrom = $request->input('date_from', now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // Global KPIs
        $kpis = [
            'total_generated' => InitialVoucher::count(),
            'total_assigned' => InitialVoucher::where('status', 'ASSIGNED')->count(),
            'total_claimed' => InitialVoucher::where('status', 'CLAIMED')->count(),
            'total_merchant_vouchers' => MerchantVoucher::count(),
            'total_redeemed' => MerchantVoucher::where('status', 'REDEEMED')->count(),
        ];

        // Calculate rates
        $kpis['claim_rate'] = $kpis['total_assigned'] > 0
            ? round(($kpis['total_claimed'] / $kpis['total_assigned']) * 100, 1)
            : 0;

        $kpis['redeem_rate'] = $kpis['total_merchant_vouchers'] > 0
            ? round(($kpis['total_redeemed'] / $kpis['total_merchant_vouchers']) * 100, 1)
            : 0;

        // Claims in date range
        $kpis['claims_in_range'] = Claim::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])->count();

        // Redeems in date range
        $kpis['redeems_in_range'] = MerchantVoucher::where('status', 'REDEEMED')
            ->whereBetween('redeemed_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->count();

        // Top PICs by claims (filtered by date range)
        $topPicsByClaims = Pic::withCount(['initialVouchers' => function ($query) use ($dateFrom, $dateTo) {
                $query->where('status', 'CLAIMED')
                      ->whereBetween('claimed_at', [$dateFrom, $dateTo . ' 23:59:59']);
            }])
            ->get()
            ->filter(function ($pic) {
                return $pic->initial_vouchers_count > 0;
            })
            ->sortByDesc('initial_vouchers_count')
            ->take(10);

        // Top PICs by redeems (through claims) - filtered by date range
        $topPicsByRedeems = DB::table('pics')
            ->join('initial_vouchers', 'pics.id', '=', 'initial_vouchers.assigned_pic_id')
            ->join('merchant_vouchers', 'initial_vouchers.id', '=', 'merchant_vouchers.initial_voucher_id')
            ->where('merchant_vouchers.status', 'REDEEMED')
            ->whereBetween('merchant_vouchers.redeemed_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select('pics.id', 'pics.name', DB::raw('COUNT(merchant_vouchers.id) as redeems_count'))
            ->groupBy('pics.id', 'pics.name')
            ->orderBy('redeems_count', 'desc')
            ->take(10)
            ->get();

        // Redemptions per merchant
        $merchantStats = Merchant::withCount(['merchantVouchers as total_vouchers'])
            ->withCount(['merchantVouchers as redeemed_vouchers' => function ($query) {
                $query->where('status', 'REDEEMED');
            }])
            ->get()
            ->map(function ($merchant) {
                $merchant->redeem_rate = $merchant->total_vouchers > 0
                    ? round(($merchant->redeemed_vouchers / $merchant->total_vouchers) * 100, 1)
                    : 0;
                return $merchant;
            })
            ->sortByDesc('redeemed_vouchers');

        // Daily trend (based on date range, max 30 days)
        $dailyTrend = [];
        $start = \Carbon\Carbon::parse($dateFrom);
        $end = \Carbon\Carbon::parse($dateTo);
        $diffInDays = $start->diffInDays($end);
        $daysToShow = min($diffInDays + 1, 30);
        
        for ($i = 0; $i < $daysToShow; $i++) {
            $date = $start->copy()->addDays($i)->format('Y-m-d');
            $dailyTrend[] = [
                'date' => $date,
                'claims' => Claim::whereDate('created_at', $date)->count(),
                'redeems' => MerchantVoucher::where('status', 'REDEEMED')
                    ->whereDate('redeemed_at', $date)
                    ->count(),
            ];
        }

        return view('admin.analytics.index', compact(
            'kpis',
            'topPicsByClaims',
            'topPicsByRedeems',
            'merchantStats',
            'dailyTrend',
            'dateFrom',
            'dateTo'
        ));
    }
}
