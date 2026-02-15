<?php

namespace App\Http\Controllers\Pic;

use App\Http\Controllers\Controller;
use App\Models\InitialVoucher;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

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

    public function exportVouchers()
    {
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');

        $pic = auth()->user()->pic;
        
        if (!$pic) {
            abort(403, 'User is not associated with a PIC account');
        }

        $vouchers = $pic->initialVouchers()
            ->where('status', 'ASSIGNED') // Only export ASSIGNED vouchers? Or all? User said "voucher yang di assign ke si pic tersebut". Usually means active ones to distribute.
            // Let's assume all ASSIGNED ones for now. If they want claimed ones, they can ask.
            // Actually, "export voucher" implies printing them for distribution, so ASSIGNED makes sense.
            ->get();

        if ($vouchers->isEmpty()) {
            return back()->with('error', 'Tidak ada voucher yang tersedia untuk diexport.');
        }

        $zip = new ZipArchive;
        $zipFileName = 'vouchers_' . $pic->id . '_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        // Ensure directory exists
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($vouchers as $index => $voucher) {
                $pdf = Pdf::loadView('pic.print.single-voucher', compact('voucher'))
                    ->setPaper('a4', 'landscape');
                
                $pdfContent = $pdf->output();
                $filename = 'voucher_' . ($index + 1) . '_' . $voucher->code . '.pdf';
                $zip->addFromString($filename, $pdfContent);
            }
            $zip->close();
        } else {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
