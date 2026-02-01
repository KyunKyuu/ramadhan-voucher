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
        $validated = $request->validate([
            'code' => 'required|string',
            'pic_id' => 'required|exists:pics,id',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
        ]);

        try {
            $claim = $this->claimService->processClaim(
                $validated['code'],
                $validated['pic_id'],
                $validated['name'],
                $validated['email']
            );

            return redirect()->route('public.vouchers', ['token' => $claim->public_token]);
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
