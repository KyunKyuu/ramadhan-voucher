<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\InitialVoucher;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pic = auth()->user()->pic;
        
        if (!$pic) {
            abort(403, 'User is not associated with a PIC account');
        }

        // Stats
        $stats = [
            'assigned' => $pic->initialVouchers()->where('status', 'ASSIGNED')->count(),
            'claimed' => $pic->initialVouchers()->where('status', 'CLAIMED')->count(),
            'redeemed' => $pic->initialVouchers()->whereHas('merchantVouchers', function($q) {
                $q->where('status', 'REDEEMED');
            })->count(),
            'commission' => $pic->initialVouchers()
                ->whereHas('merchantVouchers', function($q) {
                    $q->where('status', 'REDEEMED');
                })
                ->sum('commission_amount'),
        ];

        // Lists - using pagination and tabs could be better, but simplified for now
        // Or we pass queries and let the view handle it?
        // Let's get latest 5 or 10 for each category to display in dashboard, 
        // or just all of them if the volume isn't huge yet.
        // Given the requirement "berisi daftar voucher...", implying full lists or at least accessible.
        
        $assignedVouchers = $pic->initialVouchers()
            ->with(['batch'])
            ->where('status', 'ASSIGNED')
            ->latest()
            ->paginate(10, ['*'], 'assigned_page');

        $claimedVouchers = $pic->initialVouchers()
            ->with(['claim', 'merchantVouchers.merchant'])
            ->where('status', 'CLAIMED')
            ->latest()
            ->paginate(10, ['*'], 'claimed_page');

        $redeemedVouchers = $pic->initialVouchers()
            ->whereHas('merchantVouchers', function($q) {
                $q->where('status', 'REDEEMED');
            })
            ->with(['merchantVouchers' => function($q) {
                $q->where('status', 'REDEEMED')->with('merchant');
            }, 'claim'])
            ->latest()
            ->paginate(10, ['*'], 'redeemed_page');

        return view('pic.dashboard', compact('pic', 'stats', 'assignedVouchers', 'claimedVouchers', 'redeemedVouchers'));
    }
}
