<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InitialVoucher;
use App\Models\Pic;
use App\Models\VoucherBatch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InitialVoucherPrintController extends Controller
{
    /**
     * Show the print filter page.
     */
    public function index()
    {
        $pics = Pic::all();
        $batches = VoucherBatch::latest()->get();

        return view('admin.vouchers.print', compact('pics', 'batches'));
    }

    /**
     * Generate PDF for printing vouchers.
     */
    public function pdf(Request $request)
    {
        $query = InitialVoucher::with(['pic', 'batch']);

        // Apply filters
        if ($request->filled('pic_id')) {
            $query->where('assigned_pic_id', $request->pic_id);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: only print ASSIGNED vouchers
            $query->where('status', 'ASSIGNED');
        }

        $vouchers = $query->orderBy('code')->get();

        if ($vouchers->isEmpty()) {
            return back()->with('error', 'No vouchers found with the selected filters.');
        }

        // Generate PDF
        $pdf = Pdf::loadView('admin.print.initial-vouchers', compact('vouchers'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('vouchers-' . now()->format('Y-m-d-His') . '.pdf');
    }

    /**
     * Show print preview page (HTML for browser printing).
     */
    public function printPreview(Request $request)
    {
        $query = InitialVoucher::with(['pic', 'batch']);

        // Apply filters
        if ($request->filled('pic_id')) {
            $query->where('assigned_pic_id', $request->pic_id);
        }

        if ($request->filled('batch_id')) {
            $query->where('batch_id', $request->batch_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default: only print ASSIGNED vouchers
            $query->where('status', 'ASSIGNED');
        }

        $vouchers = $query->orderBy('code')->get();

        if ($vouchers->isEmpty()) {
            return back()->with('error', 'No vouchers found with the selected filters.');
        }

        // No need to generate QR codes here - JavaScript will handle it client-side
        return view('admin.print.vouchers-preview', compact('vouchers'));
    }
}
