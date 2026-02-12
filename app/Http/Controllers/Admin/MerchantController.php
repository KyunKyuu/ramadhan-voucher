<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerchantController extends Controller
{
    /**
     * Display a listing of merchants.
     */
    public function index()
    {
        $merchants = Merchant::withCount(['merchantVouchers', 'offers'])
            ->latest()
            ->paginate(15);

        return view('admin.merchants.index', compact('merchants'));
    }

    /**
     * Show the form for creating a new merchant.
     */
    public function create()
    {
        $templates = [
            'baju.jpeg',
            'geprek.jpeg',
            'kacamata.jpeg',
            'kerudung.jpeg',
        ];
        return view('admin.merchants.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:merchants,slug',
            'email' => 'required|email|max:255|unique:merchants,email',
            'password' => 'required|string|min:8|confirmed',
            'logo_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'voucher_template' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'offer_title' => 'nullable|string|max:255',
            'offer_description' => 'nullable|string',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:PERCENT,FIXED',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['password'] = bcrypt($validated['password']);

        // Extract offer data
        $offerData = [
            'title' => $validated['offer_title'] ?? null,
            'description' => $validated['offer_description'] ?? null,
            'discount_value' => $validated['discount_value'] ?? 0,
            'discount_type' => $validated['discount_type'] ?? 'PERCENT',
            'is_active' => true,
        ];
        
        // Remove offer fields from merchant data
        unset($validated['offer_title'], $validated['offer_description'], $validated['discount_value'], $validated['discount_type'], $validated['product_images']);

        $merchant = Merchant::create($validated);

        // Create Default Offer if provided
        if (!empty($offerData['title'])) {
            $offer = $merchant->offers()->create($offerData);

            // Handle Product Images
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    
                    $offer->images()->create([
                        'path' => 'images/products/' . $filename,
                    ]);
                }
            }
        }

        // Create user for merchant login
        \App\Models\User::create([
            'name' => $merchant->name,
            'email' => $merchant->email,
            'password' => $merchant->password,
            'role' => 'MERCHANT',
            'merchant_id' => $merchant->id,
        ]);

        return redirect()
            ->route('admin.merchants.index')
            ->with('success', 'Merchant berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified merchant.
     */
    public function edit(Merchant $merchant)
    {
        $templates = [
            'baju.jpeg',
            'geprek.jpeg',
            'kacamata.jpeg',
            'kerudung.jpeg',
        ];
        return view('admin.merchants.edit', compact('merchant', 'templates'));
    }

    /**
     * Update the specified merchant.
     */
    public function update(Request $request, Merchant $merchant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:merchants,slug,' . $merchant->id,
            'email' => 'required|email|max:255|unique:merchants,email,' . $merchant->id,
            'password' => 'nullable|string|min:8|confirmed',
            'logo_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'voucher_template' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'offer_title' => 'nullable|string|max:255',
            'offer_description' => 'nullable|string',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:PERCENT,FIXED',
            'product_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Extract offer data
        $offerData = [
            'title' => $validated['offer_title'] ?? null,
            'description' => $validated['offer_description'] ?? null,
            'discount_value' => $validated['discount_value'] ?? 0,
            'discount_type' => $validated['discount_type'] ?? 'PERCENT',
            'is_active' => true,
        ];

        // Remove offer fields from merchant data
        unset($validated['offer_title'], $validated['offer_description'], $validated['discount_value'], $validated['discount_type'], $validated['product_images']);

        $merchant->update($validated);

        // Update or Create Default Offer
        if (!empty($offerData['title'])) {
            // Check if merchant has an active offer, update it, otherwise create new
            $offer = $merchant->offer; // using hasOne relationship
            if ($offer) {
                $offer->update($offerData);
            } else {
                $offer = $merchant->offers()->create($offerData);
            }

            // Handle Product Images (Append)
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('images/products'), $filename);
                    
                    $offer->images()->create([
                        'path' => 'images/products/' . $filename,
                    ]);
                }
            }
        }

        // Update associated user email and password
        $user = \App\Models\User::where('merchant_id', $merchant->id)->first();
        if ($user) {
            $user->email = $validated['email'];
            if (isset($validated['password'])) {
                $user->password = $validated['password'];
            }
            $user->save();
        } else {
             // Create user if not exists (fallback for existing merchants)
             if (isset($validated['password'])) {
                 \App\Models\User::create([
                    'name' => $merchant->name,
                    'email' => $merchant->email,
                    'password' => $merchant->password, 
                    'role' => 'MERCHANT',
                    'merchant_id' => $merchant->id,
                ]);
             }
        }

        return redirect()
            ->route('admin.merchants.index')
            ->with('success', 'Merchant berhasil diupdate');
    }

    /**
     * Remove the specified merchant.
     */
    public function destroy(Merchant $merchant)
    {
        // Check if merchant has vouchers (removed due to soft delete cascade)
        // if ($merchant->merchantVouchers()->count() > 0) {
        //     return back()->with('error', 'Merchant tidak dapat dihapus karena sudah memiliki voucher');
        // }

        $merchant->delete();

        return redirect()
            ->route('admin.merchants.index')
            ->with('success', 'Merchant berhasil dihapus');
    }
}
