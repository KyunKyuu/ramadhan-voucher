<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pic;
use App\Models\VoucherBatch;
use App\Services\InitialVoucherAssignService;
use Illuminate\Http\Request;

class InitialVoucherAssignController extends Controller
{
    protected $assignService;

    public function __construct(InitialVoucherAssignService $assignService)
    {
        $this->assignService = $assignService;
    }

    /**
     * Show the form for assigning vouchers.
     */
    public function create()
    {
        $pics = Pic::where('is_active', true)->get();
        $batches = VoucherBatch::latest()->get();
        $availableCount = $this->assignService->getAvailableCount();

        return view('admin.vouchers.assign', compact('pics', 'batches', 'availableCount'));
    }

    /**
     * Assign vouchers to a PIC.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pic_id' => 'required|exists:pics,id',
            'qty' => 'required|integer|min:1',
            'batch_id' => 'nullable|exists:voucher_batches,id',
        ]);

        try {
            $count = $this->assignService->assign(
                $validated['pic_id'],
                $validated['qty'],
                $validated['batch_id'] ?? null
            );

            $pic = Pic::find($validated['pic_id']);

            return redirect()
                ->route('admin.vouchers.assign')
                ->with('success', "Successfully assigned {$count} vouchers to {$pic->name}");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to assign vouchers: ' . $e->getMessage());
        }
    }
}
