<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show merchant analytics.
     */
    public function index(Request $request)
    {
        $merchantId = Auth::user()->merchant_id;

        // Date filter (default: last 30 days)
        $dateFrom = $request->input('date_from', now()->subDays(30)->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->format('Y-m-d'));

        // KPIs
        $kpis = [
            'total_redeemed' => MerchantVoucher::where('merchant_id', $merchantId)
                ->where('status', 'REDEEMED')
                ->count(),

            'redeemed_today' => MerchantVoucher::where('merchant_id', $merchantId)
                ->where('status', 'REDEEMED')
                ->whereDate('redeemed_at', today())
                ->count(),

            'redeemed_in_range' => MerchantVoucher::where('merchant_id', $merchantId)
                ->where('status', 'REDEEMED')
                ->whereBetween('redeemed_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->count(),

            'unique_users' => MerchantVoucher::where('merchant_vouchers.merchant_id', $merchantId)
                ->where('merchant_vouchers.status', 'REDEEMED')
                ->join('initial_vouchers', 'merchant_vouchers.initial_voucher_id', '=', 'initial_vouchers.id')
                ->join('claims', 'initial_vouchers.id', '=', 'claims.initial_voucher_id')
                ->distinct('claims.email')
                ->count('claims.email'),

            'active_vouchers' => MerchantVoucher::where('merchant_id', $merchantId)
                ->where('status', 'ACTIVE')
                ->count(),
        ];

        // Top PICs (who brought the most redeems)
        $topPics = DB::table('merchant_vouchers')
            ->join('initial_vouchers', 'merchant_vouchers.initial_voucher_id', '=', 'initial_vouchers.id')
            ->join('pics', 'initial_vouchers.assigned_pic_id', '=', 'pics.id')
            ->where('merchant_vouchers.merchant_id', $merchantId)
            ->where('merchant_vouchers.status', 'REDEEMED')
            ->select('pics.name', DB::raw('COUNT(merchant_vouchers.id) as redeems_count'))
            ->groupBy('pics.id', 'pics.name')
            ->orderBy('redeems_count', 'desc')
            ->take(10)
            ->get();

        // Daily trend (last 7 days)
        $dailyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyTrend[] = [
                'date' => $date,
                'redeems' => MerchantVoucher::where('merchant_id', $merchantId)
                    ->where('status', 'REDEEMED')
                    ->whereDate('redeemed_at', $date)
                    ->count(),
            ];
        }

        // Top redeemers (staff)
        $topStaff = MerchantVoucher::where('merchant_id', $merchantId)
            ->where('status', 'REDEEMED')
            ->with('redeemedBy')
            ->select('redeemed_by_merchant_user_id', DB::raw('COUNT(*) as redeems_count'))
            ->groupBy('redeemed_by_merchant_user_id')
            ->orderBy('redeems_count', 'desc')
            ->take(10)
            ->get();

        return view('merchant.analytics.index', compact(
            'kpis',
            'topPics',
            'dailyTrend',
            'topStaff',
            'dateFrom',
            'dateTo'
        ));
    }
}
