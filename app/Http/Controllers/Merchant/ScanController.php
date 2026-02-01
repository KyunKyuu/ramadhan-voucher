<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Services\RedeemService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ScanController extends Controller
{
    protected $redeemService;

    public function __construct(RedeemService $redeemService)
    {
        $this->redeemService = $redeemService;
    }

    /**
     * Show scan page.
     */
    public function index()
    {
        return view('merchant.scan');
    }

    /**
     * Validate voucher code.
     */
    public function validateVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $merchantId = Auth::user()->merchant_id;
        
        try {
            $voucher = $this->redeemService->validateVoucher($request->code, $merchantId);
            
            return response()->json([
                'valid' => true,
                'voucher' => [
                    'code' => $voucher->code,
                    'status' => $voucher->status,
                    'user_name' => $voucher->initialVoucher->claim->name ?? 'N/A',
                    'user_email' => $voucher->initialVoucher->claim->email ?? 'N/A',
                    'pic_name' => $voucher->initialVoucher->pic->name ?? 'N/A',
                    'offer_title' => $voucher->merchant->offer->title ?? 'No offer',
                    'offer_discount' => $voucher->merchant->offer->formatted_discount ?? '',
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'valid' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Redeem voucher.
     */
    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $merchantId = Auth::user()->merchant_id;
        $userId = Auth::id();

        try {
            $voucher = $this->redeemService->redeemVoucher($request->code, $merchantId, $userId);
            
            return response()->json([
                'success' => true,
                'message' => 'Voucher berhasil diredeem!',
                'voucher' => [
                    'code' => $voucher->code,
                    'redeemed_at' => $voucher->redeemed_at->format('d M Y H:i'),
                    'redeemed_by' => $voucher->redeemedBy->name,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
