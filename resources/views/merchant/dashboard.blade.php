@extends('layouts.merchant')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
        <p class="text-gray-600">Ringkasan aktivitas voucher</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600 mb-1">Hari Ini</div>
            <div class="text-3xl font-bold text-blue-600">{{ $kpis['redeemed_today'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Voucher diredeem</div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600 mb-1">Total</div>
            <div class="text-3xl font-bold text-green-600">{{ $kpis['total_redeemed'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Voucher diredeem</div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600 mb-1">Aktif</div>
            <div class="text-3xl font-bold text-purple-600">{{ $kpis['active_vouchers'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Belum diredeem</div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-600 mb-1">Total</div>
            <div class="text-3xl font-bold text-gray-900">{{ $kpis['total_vouchers'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Semua voucher</div>
        </div>
    </div>

    <!-- Recent Redemptions -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-900">Redemption Terbaru</h3>
        </div>
        <div class="divide-y">
            @forelse($recentRedemptions as $redemption)
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $redemption->initialVoucher->claim->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">{{ $redemption->code }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $redemption->redeemed_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Redeemed
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    Belum ada redemption
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('merchant.scan') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700">
            <div class="text-3xl mb-2">📱</div>
            <div class="font-semibold">Scan Voucher</div>
        </a>
        <a href="{{ route('merchant.redemptions') }}" class="bg-gray-600 text-white p-4 rounded-lg text-center hover:bg-gray-700">
            <div class="text-3xl mb-2">📜</div>
            <div class="font-semibold">Lihat Riwayat</div>
        </a>
    </div>
</div>
@endsection
