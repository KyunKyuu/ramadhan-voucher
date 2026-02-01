<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedemptionController extends Controller
{
    /**
     * Show redemption history.
     */
    public function index(Request $request)
    {
        $merchantId = Auth::user()->merchant_id;

        $query = MerchantVoucher::with(['initialVoucher.claim', 'initialVoucher.pic', 'redeemedBy'])
            ->where('merchant_id', $merchantId)
            ->where('status', 'REDEEMED');

        // Filter by date if provided
        if ($request->filled('date')) {
            $query->whereDate('redeemed_at', $request->date);
        }

        $redemptions = $query->latest('redeemed_at')
            ->paginate(20);

        return view('merchant.redemptions', compact('redemptions'));
    }
}
