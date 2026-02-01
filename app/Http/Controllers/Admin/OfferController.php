<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\MerchantOffer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of offers.
     */
    public function index()
    {
        $offers = MerchantOffer::with('merchant')
            ->latest()
            ->paginate(15);

        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new offer.
     */
    public function create()
    {
        $merchants = Merchant::where('is_active', true)->get();
        return view('admin.offers.create', compact('merchants'));
    }

    /**
     * Store a newly created offer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'title' => 'required|string|max:100',
            'discount_type' => 'required|in:PERCENT,AMOUNT',
            'discount_value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Validate discount value based on type
        if ($validated['discount_type'] === 'PERCENT' && $validated['discount_value'] > 100) {
            return back()
                ->withInput()
                ->withErrors(['discount_value' => 'Persentase diskon tidak boleh lebih dari 100%']);
        }

        $validated['is_active'] = $request->has('is_active');

        MerchantOffer::create($validated);

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'Offer berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified offer.
     */
    public function edit(MerchantOffer $offer)
    {
        $merchants = Merchant::where('is_active', true)->get();
        return view('admin.offers.edit', compact('offer', 'merchants'));
    }

    /**
     * Update the specified offer.
     */
    public function update(Request $request, MerchantOffer $offer)
    {
        $validated = $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'title' => 'required|string|max:100',
            'discount_type' => 'required|in:PERCENT,AMOUNT',
            'discount_value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        // Validate discount value based on type
        if ($validated['discount_type'] === 'PERCENT' && $validated['discount_value'] > 100) {
            return back()
                ->withInput()
                ->withErrors(['discount_value' => 'Persentase diskon tidak boleh lebih dari 100%']);
        }

        $validated['is_active'] = $request->has('is_active');

        $offer->update($validated);

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'Offer berhasil diupdate');
    }

    /**
     * Remove the specified offer.
     */
    public function destroy(MerchantOffer $offer)
    {
        $offer->delete();

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'Offer berhasil dihapus');
    }
}
