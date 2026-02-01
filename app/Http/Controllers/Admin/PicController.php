<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pic;
use Illuminate\Http\Request;

class PicController extends Controller
{
    /**
     * Display a listing of PICs.
     */
    public function index()
    {
        $pics = Pic::withCount('initialVouchers')
            ->latest()
            ->paginate(15);

        return view('admin.pics.index', compact('pics'));
    }

    /**
     * Show the form for creating a new PIC.
     */
    public function create()
    {
        return view('admin.pics.create');
    }

    /**
     * Store a newly created PIC.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:pics,code',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Pic::create($validated);

        return redirect()
            ->route('admin.pics.index')
            ->with('success', 'PIC berhasil ditambahkan');
    }

    /**
     * Display the specified PIC with vouchers.
     */
    public function show(Pic $pic)
    {
        // Load vouchers with claim and batch relationships
        $pic->load(['initialVouchers' => function($query) {
            $query->with(['claim', 'batch'])
                  ->orderByRaw("CASE WHEN status = 'ASSIGNED' THEN 1 WHEN status = 'CLAIMED' THEN 2 ELSE 3 END")
                  ->orderBy('code');
        }]);

        // Calculate statistics
        $stats = [
            'total' => $pic->initialVouchers->count(),
            'assigned' => $pic->initialVouchers->where('status', 'ASSIGNED')->count(),
            'claimed' => $pic->initialVouchers->where('status', 'CLAIMED')->count(),
        ];

        return view('admin.pics.show', compact('pic', 'stats'));
    }

    /**
     * Show the form for editing the specified PIC.
     */
    public function edit(Pic $pic)
    {
        return view('admin.pics.edit', compact('pic'));
    }

    /**
     * Update the specified PIC.
     */
    public function update(Request $request, Pic $pic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:20|unique:pics,code,' . $pic->id,
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $pic->update($validated);

        return redirect()
            ->route('admin.pics.index')
            ->with('success', 'PIC berhasil diupdate');
    }

    /**
     * Remove the specified PIC.
     */
    public function destroy(Pic $pic)
    {
        // Check if PIC has assigned vouchers
        if ($pic->initialVouchers()->count() > 0) {
            return back()->with('error', 'PIC tidak dapat dihapus karena masih memiliki voucher yang di-assign');
        }

        $pic->delete();

        return redirect()
            ->route('admin.pics.index')
            ->with('success', 'PIC berhasil dihapus');
    }
}
