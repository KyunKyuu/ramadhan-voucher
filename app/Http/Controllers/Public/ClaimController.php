<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\ClaimService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClaimController extends Controller
{
    protected $claimService;

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }

    /**
     * Show the claim form.
     *
     * @param string $code
     * @return \Illuminate\View\View
     */
    public function show(string $code)
    {
        try {
            $voucher = $this->claimService->validateVoucherForClaim($code);
            $pics = \App\Models\Pic::orderBy('name')->get();
            
            return view('public.claim', [
                'voucher' => $voucher,
                'code' => $code,
                'pics' => $pics,
            ]);
        } catch (ValidationException $e) {
            return view('public.claim-error', [
                'code' => $code,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Process the claim.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Restore validation logic
            // The QueryException happened because validation was bypassed.
            // Since we disabled the ValidatePostSize middleware, validation should now run correctly.
            $validated = $request->validate([
                'code' => 'required|string',
                'pic_id' => 'required|exists:pics,id',
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'required|string|max:30',
                'zakat_fitrah_amount' => 'nullable|numeric|min:0',
                'infaq_amount' => 'nullable|numeric|min:0',
                'sodaqoh_amount' => 'nullable|numeric|min:0',
            ]);
            $zakatFitrahAmount = isset($validated['zakat_fitrah_amount'])
                ? (float) $validated['zakat_fitrah_amount']
                : 0;
            $infaqAmount = isset($validated['infaq_amount'])
                ? (float) $validated['infaq_amount']
                : 0;
            $sodaqohAmount = isset($validated['sodaqoh_amount'])
                ? (float) $validated['sodaqoh_amount']
                : 0;

            $claim = $this->claimService->processClaim(
                $validated['code'],
                $validated['pic_id'],
                $validated['name'],
                $validated['email'],
                $validated['phone'],
                $zakatFitrahAmount,
                $infaqAmount,
                $sodaqohAmount
            );

            return redirect()->route('public.vouchers', ['token' => $claim->public_token]);
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Claim Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            
            // Temporary: Show full error details for debugging
            return back()
                ->withInput()
                ->with('error', 'Error Detail: ' . $e->getMessage());
        }
    }
}
