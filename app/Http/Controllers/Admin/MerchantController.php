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
        return view('admin.merchants.create');
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
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['password'] = bcrypt($validated['password']);

        $merchant = Merchant::create($validated);

        // Create user for merchant login
        \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
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
        return view('admin.merchants.edit', compact('merchant'));
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

        $merchant->update($validated);

        // Update associated user email and password
        $user = \App\Models\User::where('merchant_id', $merchant->id)->first();
        if ($user) {
            $user->email = $validated['email'];
            if (isset($validated['password'])) {
                $user->password = $validated['password'];
            }
            $user->save();
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
        // Check if merchant has vouchers
        if ($merchant->merchantVouchers()->count() > 0) {
            return back()->with('error', 'Merchant tidak dapat dihapus karena sudah memiliki voucher');
        }

        $merchant->delete();

        return redirect()
            ->route('admin.merchants.index')
            ->with('success', 'Merchant berhasil dihapus');
    }
}
