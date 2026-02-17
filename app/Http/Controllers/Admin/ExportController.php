<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Claim;
use App\Models\MerchantVoucher;
use App\Models\InitialVoucher;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Show export center.
     */
    public function index()
    {
        $stats = [
            'total_claims' => Claim::count(),
            'total_redeems' => MerchantVoucher::where('status', 'REDEEMED')->count(),
            'total_vouchers' => InitialVoucher::count(),
        ];

        return view('admin.exports.index', compact('stats'));
    }

    /**
     * Export claims to CSV.
     */
    public function claims(Request $request)
    {
        $query = Claim::with(['initialVoucher.pic', 'initialVoucher.batch']);

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $claims = $query->orderBy('created_at', 'desc')->get();

        $filename = 'claims_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($claims) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'ID',
                'Voucher Code',
                'Name',
                'Email',
                'Phone',
                'Zakat Fitrah',
                'Zakat Mal',
                'Infaq',
                'Sodaqoh',
                'Payment Method',
                'Transfer Destination',
                'Transfer Proof Path',
                'PIC',
                'Batch',
                'Claimed At',
                'Public Token'
            ]);

            // CSV Rows
            foreach ($claims as $claim) {
                fputcsv($handle, [
                    $claim->id,
                    $claim->initialVoucher->code ?? 'N/A',
                    $claim->name,
                    $claim->email,
                    $claim->phone ?? '',
                    $claim->zakat_fitrah_amount ?? 0,
                    $claim->zakat_mal_amount ?? 0,
                    $claim->infaq_amount ?? 0,
                    $claim->sodaqoh_amount ?? 0,
                    $claim->payment_method ?? 'cash',
                    $claim->transfer_destination ?? '',
                    $claim->transfer_proof_path ?? '',
                    $claim->initialVoucher->pic->name ?? 'N/A',
                    $claim->initialVoucher->batch->name ?? 'N/A',
                    $claim->created_at->format('Y-m-d H:i:s'),
                    $claim->public_token,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export redemptions to CSV.
     */
    public function redeems(Request $request)
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

        $redeems = $query->orderBy('redeemed_at', 'desc')->get();

        $filename = 'redeems_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($redeems) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'ID',
                'Voucher Code',
                'Merchant',
                'Customer Name',
                'Customer Email',
                'PIC',
                'Redeemed At',
                'Redeemed By'
            ]);

            // CSV Rows
            foreach ($redeems as $redeem) {
                fputcsv($handle, [
                    $redeem->id,
                    $redeem->code,
                    $redeem->merchant->name ?? 'N/A',
                    $redeem->initialVoucher->claim->name ?? 'N/A',
                    $redeem->initialVoucher->claim->email ?? 'N/A',
                    $redeem->initialVoucher->pic->name ?? 'N/A',
                    $redeem->redeemed_at ? $redeem->redeemed_at->format('Y-m-d H:i:s') : 'N/A',
                    $redeem->redeemedBy->name ?? 'N/A',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export vouchers to CSV.
     */
    public function vouchers(Request $request)
    {
        $query = InitialVoucher::with(['batch', 'pic', 'claim']);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vouchers = $query->orderBy('created_at', 'desc')->get();

        $filename = 'vouchers_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($vouchers) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, [
                'ID',
                'Code',
                'Batch',
                'Status',
                'PIC',
                'Claimed By',
                'Claimed At',
                'Created At'
            ]);

            // CSV Rows
            foreach ($vouchers as $voucher) {
                fputcsv($handle, [
                    $voucher->id,
                    $voucher->code,
                    $voucher->batch->name ?? 'N/A',
                    $voucher->status,
                    $voucher->pic->name ?? 'N/A',
                    $voucher->claim->name ?? 'N/A',
                    $voucher->claimed_at ? $voucher->claimed_at->format('Y-m-d H:i:s') : 'N/A',
                    $voucher->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
