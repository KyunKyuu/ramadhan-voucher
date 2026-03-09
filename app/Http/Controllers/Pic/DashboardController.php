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
            'redeemed' => $pic->initialVouchers()->whereHas('merchantVouchers', function ($q) {
                $q->where('status', 'REDEEMED');
            })->count(),
            'commission' => $pic->initialVouchers()
                ->whereHas('merchantVouchers', function ($q) {
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
            ->with(['batch', 'claim'])
            ->where('status', 'ASSIGNED')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10, ['*'], 'assigned_page');

        $claimedPerPage = request()->input('claimed_per_page', 10);

        $claimedVouchers = $pic->initialVouchers()
            ->with(['claim', 'merchantVouchers.merchant'])
            ->where('status', 'CLAIMED')
            ->orderBy('claimed_at', 'desc')
            ->orderBy('id', 'desc')
            ->paginate($claimedPerPage, ['*'], 'claimed_page');

        $redeemedVouchers = $pic->initialVouchers()
            ->whereHas('merchantVouchers', function ($q) {
                $q->where('status', 'REDEEMED');
            })
            ->with([
                'merchantVouchers' => function ($q) {
                    $q->where('status', 'REDEEMED')->with('merchant');
                },
                'claim'
            ])
            ->orderBy('updated_at', 'desc')
            ->orderBy('id', 'desc')
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

    public function exportData(Request $request)
    {
        $pic = auth()->user()->pic;

        if (!$pic) {
            abort(403, 'User is not associated with a PIC account');
        }

        $type = $request->input('type', 'claimed'); // Default: claimed
        $dateFrom = $request->filled('date_from') ? \Carbon\Carbon::parse($request->date_from)->startOfDay() : null;
        $dateTo = $request->filled('date_to') ? \Carbon\Carbon::parse($request->date_to)->endOfDay() : null;

        if ($type === 'redeem') {
            $query = $pic->initialVouchers()
                ->whereHas('merchantVouchers', function ($q) {
                    $q->where('status', 'REDEEMED');
                })
                ->with([
                    'merchantVouchers' => function ($q) {
                        $q->where('status', 'REDEEMED')->with('merchant');
                    },
                    'claim'
                ]);

            if ($dateFrom) {
                $query->whereHas('merchantVouchers', function ($q) use ($dateFrom) {
                    $q->where('status', 'REDEEMED')->whereDate('redeemed_at', '>=', $dateFrom);
                });
            }
            if ($dateTo) {
                $query->whereHas('merchantVouchers', function ($q) use ($dateTo) {
                    $q->where('status', 'REDEEMED')->whereDate('redeemed_at', '<=', $dateTo);
                });
            }

            $vouchers = $query->get();
            $filename = 'redeem_data_' . now()->format('Y-m-d_His') . '.csv';

            return response()->streamDownload(function () use ($vouchers, $dateFrom, $dateTo) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['No', 'Voucher Code', 'Merchant', 'Donatur', 'Zakat Fitrah', 'Zakat Mal', 'Infaq', 'Sodaqoh', 'Total Donasi', 'Komisi', 'Tanggal Redeem']);

                $count = 1;
                foreach ($vouchers as $voucher) {
                    foreach ($voucher->merchantVouchers as $mv) {
                        if ($mv->status === 'REDEEMED') {
                            // Extra check for date range in rows
                            if ($dateFrom && $mv->redeemed_at->lt($dateFrom)) continue;
                            if ($dateTo && $mv->redeemed_at->gt($dateTo)) continue;

                            $zf = (float)($voucher->claim->zakat_fitrah_amount ?? 0);
                            $zm = (float)($voucher->claim->zakat_mal_amount ?? 0);
                            $inf = (float)($voucher->claim->infaq_amount ?? 0);
                            $sod = (float)($voucher->claim->sodaqoh_amount ?? 0);
                            $total = $zf + $zm + $inf + $sod;

                            fputcsv($handle, [
                                $count++,
                                $voucher->code,
                                $mv->merchant->name ?? '-',
                                $voucher->claim->name ?? '-',
                                $zf,
                                $zm,
                                $inf,
                                $sod,
                                $total,
                                $voucher->commission_amount,
                                $mv->redeemed_at ? $mv->redeemed_at->format('Y-m-d H:i:s') : '-'
                            ]);
                        }
                    }
                }
                fclose($handle);
            }, $filename, ['Content-Type' => 'text/csv']);
        } else {
            // Default: claimed
            $query = $pic->initialVouchers()
                ->whereIn('status', ['CLAIMED', 'REDEEMED']) // Redeemed also means it was claimed
                ->with(['claim']);

            if ($dateFrom) {
                $query->whereDate('claimed_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('claimed_at', '<=', $dateTo);
            }

            $vouchers = $query->orderBy('claimed_at', 'desc')->get();
            $filename = 'claimed_data_' . now()->format('Y-m-d_His') . '.csv';

            return response()->streamDownload(function () use ($vouchers) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['No', 'Voucher Code', 'Donatur', 'Zakat Fitrah', 'Zakat Mal', 'Infaq', 'Sodaqoh', 'Total Donasi', 'Tanggal Claim', 'Status Voucher', 'Status Dana']);

                $count = 1;
                foreach ($vouchers as $voucher) {
                    $zf = (float)($voucher->claim->zakat_fitrah_amount ?? 0);
                    $zm = (float)($voucher->claim->zakat_mal_amount ?? 0);
                    $inf = (float)($voucher->claim->infaq_amount ?? 0);
                    $sod = (float)($voucher->claim->sodaqoh_amount ?? 0);
                    $total = $zf + $zm + $inf + $sod;

                    fputcsv($handle, [
                        $count++,
                        $voucher->code,
                        $voucher->claim->name ?? '-',
                        $zf,
                        $zm,
                        $inf,
                        $sod,
                        $total,
                        $voucher->claimed_at ? $voucher->claimed_at->format('Y-m-d H:i:s') : '-',
                        $voucher->status,
                        $voucher->claim->verification_status ?? 'PENDING'
                    ]);
                }
                fclose($handle);
            }, $filename, ['Content-Type' => 'text/csv']);
        }
    }
}
