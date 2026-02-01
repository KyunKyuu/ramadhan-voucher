<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MerchantVoucher;
use Illuminate\Http\Request;

class RedeemDataController extends Controller
{
    /**
     * Display redemptions data with filters.
     */
    public function index(Request $request)
    {
        $query = MerchantVoucher::with([
            'merchant',
            'initialVoucher.claim',
            'initialVoucher.pic',
            'redeemedBy'
        ])->where('status', 'REDEEMED');

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('redeemed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('redeemed_at', '<=', $request->date_to);
        }

        // Merchant filter
        if ($request->filled('merchant_id')) {
            $query->where('merchant_id', $request->merchant_id);
        }

        // PIC filter
        if ($request->filled('pic_id')) {
            $query->whereHas('initialVoucher', function ($q) use ($request) {
                $q->where('assigned_pic_id', $request->pic_id);
            });
        }

        // Search by customer name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('initialVoucher.claim', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $redemptions = $query->orderBy('redeemed_at', 'desc')->paginate(20);

        // Get merchants and PICs for filter dropdowns
        $merchants = \App\Models\Merchant::orderBy('name')->get();
        $pics = \App\Models\Pic::orderBy('name')->get();

        // Stats
        $stats = [
            'total_redeemed' => MerchantVoucher::where('status', 'REDEEMED')->count(),
            'today_redeemed' => MerchantVoucher::where('status', 'REDEEMED')
                ->whereDate('redeemed_at', today())->count(),
            'this_week_redeemed' => MerchantVoucher::where('status', 'REDEEMED')
                ->whereBetween('redeemed_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.redeems.index', compact('redemptions', 'merchants', 'pics', 'stats'));
    }

    /**
     * Show redemption details.
     */
    public function show($id)
    {
        $redemption = MerchantVoucher::with([
            'merchant',
            'merchant.offer',
            'initialVoucher.claim',
            'initialVoucher.pic',
            'initialVoucher.batch',
            'redeemedBy'
        ])->where('status', 'REDEEMED')->findOrFail($id);

        return view('admin.redeems.show', compact('redemption'));
    }
}
