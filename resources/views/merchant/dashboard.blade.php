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

    <!-- Quick Actions & Voucher Preview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="md:col-span-2 grid grid-cols-2 gap-4">
            <a href="{{ route('merchant.scan') }}" class="bg-blue-600 text-white p-6 rounded-xl flex flex-col items-center justify-center hover:bg-blue-700 transition shadow-lg">
                <div class="text-4xl mb-3">📱</div>
                <div class="font-bold text-lg">Scan Voucher</div>
            </a>
            <a href="{{ route('merchant.redemptions') }}" class="bg-gray-800 text-white p-6 rounded-xl flex flex-col items-center justify-center hover:bg-gray-900 transition shadow-lg">
                <div class="text-4xl mb-3">📜</div>
                <div class="font-bold text-lg">Lihat Riwayat</div>
            </a>
        </div>

        <!-- Voucher Template Preview -->
        <div class="bg-white rounded-xl shadow-lg p-4 flex flex-col items-center">
            <h3 class="font-semibold text-gray-900 mb-3">Desain Voucher Anda</h3>
            <div class="relative w-full aspect-[2/1] rounded-lg overflow-hidden border border-gray-200 shadow-sm group hover:shadow-md transition">
                @php
                    $template = auth()->user()->merchant->voucher_template ?? 'baju.jpeg';
                @endphp
                <img src="{{ asset('images/voucher/' . $template) }}" alt="Voucher Template" class="w-full h-full object-cover">
            </div>
            <p class="text-xs text-center text-gray-500 mt-2">
                Template: {{ $template }}
                <br>
                <span class="text-gray-400">Hubungi Admin untuk mengubah</span>
            </p>
        </div>
    </div>
</div>
@endsection
