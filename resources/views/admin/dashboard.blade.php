@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
        <p class="text-gray-600">Overview sistem voucher Ramadhan</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Vouchers Generated -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Voucher</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['total_vouchers_generated']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">🎫</span>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Assigned: {{ number_format($kpis['total_assigned']) }}
            </div>
        </div>

        <!-- Claims -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Klaim</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['total_claimed']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Conversion: {{ $kpis['claim_rate'] }}%
            </div>
        </div>

        <!-- Merchant Vouchers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Voucher Merchant</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($kpis['total_merchant_vouchers']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">🏪</span>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                Redeemed: {{ number_format($kpis['total_redeemed']) }}
            </div>
        </div>

        <!-- Redemption Rate -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Redemption Rate</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $kpis['redeem_rate'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">
                {{ number_format($kpis['total_redeemed']) }} / {{ number_format($kpis['total_merchant_vouchers']) }}
            </div>
        </div>
    </div>

    <!-- Secondary KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- PICs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">PICs</h3>
                <a href="{{ route('admin.pics.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat semua →</a>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <p class="text-2xl font-bold text-gray-900">{{ $kpis['total_pics'] }}</p>
                    <p class="text-sm text-gray-600">Total PICs</p>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-green-600">{{ $kpis['active_pics'] }}</p>
                    <p class="text-sm text-gray-600">Aktif</p>
                </div>
            </div>
        </div>

        <!-- Merchants -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Merchants</h3>
                <a href="{{ route('admin.merchants.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat semua →</a>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <p class="text-2xl font-bold text-gray-900">{{ $kpis['total_merchants'] }}</p>
                    <p class="text-sm text-gray-600">Total Merchants</p>
                </div>
                <div class="flex-1">
                    <p class="text-2xl font-bold text-green-600">{{ $kpis['active_merchants'] }}</p>
                    <p class="text-sm text-gray-600">Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Claims -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Klaim Terbaru</h3>
            </div>
            <div class="divide-y">
                @forelse($recentClaims as $claim)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $claim->name }}</p>
                                <p class="text-sm text-gray-600">{{ $claim->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $claim->created_at->diffForHumans() }}</p>
                                @if($claim->initialVoucher && $claim->initialVoucher->pic)
                                    <p class="text-xs text-gray-500">PIC: {{ $claim->initialVoucher->pic->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Belum ada klaim
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Redemptions -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Redemption Terbaru</h3>
            </div>
            <div class="divide-y">
                @forelse($recentRedemptions as $redemption)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $redemption->merchant->name }}</p>
                                <p class="text-sm text-gray-600">{{ $redemption->code }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $redemption->redeemed_at->diffForHumans() }}</p>
                                @if($redemption->redeemedBy)
                                    <p class="text-xs text-gray-500">By: {{ $redemption->redeemedBy->name }}</p>
                                @endif
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
    </div>
</div>
@endsection
