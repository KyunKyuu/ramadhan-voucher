@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
        <p class="text-gray-600">Overview sistem voucher Ramadhan</p>
    </div>

    <!-- Donation Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Zakat Fitrah -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Zakat Fitrah</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($donations['zakat_fitrah'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">🕌</span>
                </div>
            </div>
        </div>

        <!-- Zakat Mal -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-cyan-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Zakat Mal</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($donations['zakat_mal'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-cyan-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">💠</span>
                </div>
            </div>
        </div>

        <!-- Infaq -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Infaq</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($donations['infaq'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">🤝</span>
                </div>
            </div>
        </div>

        <!-- Sodaqoh -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Sodaqoh</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($donations['sodaqoh'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">🤲</span>
                </div>
            </div>
        </div>

        <!-- Grand Total -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-600 bg-gradient-to-r from-white to-purple-50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Penyaluran</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($donations['total'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">💰</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Fund Verification Stats -->
    <h3 class="text-lg font-semibold text-gray-900 mt-8 mb-4">Status Verifikasi Dana (Reconcile)</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Verified -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Dana Terverifikasi</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($fundStats['verified'], 0, ',', '.') }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ number_format($fundStats['verified_count']) }} Transaksi</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">✅</span>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Verifikasi</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($fundStats['pending'], 0, ',', '.') }}</p>
                    <p class="text-xs text-yellow-600 mt-1">{{ number_format($fundStats['pending_count']) }} Transaksi</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">⏳</span>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.fund-verification.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Verifikasi Sekarang &rarr;</a>
            </div>
        </div>

        <!-- Anomaly -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Anomali (Selisih/Masalah)</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($fundStats['anomaly'], 0, ',', '.') }}</p>
                    <p class="text-xs text-red-600 mt-1">{{ number_format($fundStats['anomaly_count']) }} Transaksi</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-xl">⚠️</span>
                </div>
            </div>
            @if($fundStats['anomaly_count'] > 0)
            <div class="mt-4">
                <a href="{{ route('admin.claims.index') }}" class="text-sm text-red-600 hover:text-red-800 font-medium">Lihat Detail &rarr;</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Penyaluran Harian (30 Hari Terakhir)</h3>
        <div class="relative h-80 w-full">
            <canvas id="donationChart"></canvas>
        </div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('donationChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Zakat Fitrah',
                        data: {!! json_encode($chartData['zakat_fitrah']) !!},
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Zakat Mal',
                        data: {!! json_encode($chartData['zakat_mal']) !!},
                        borderColor: '#06b6d4', // cyan-500
                        backgroundColor: 'rgba(6, 182, 212, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Infaq',
                        data: {!! json_encode($chartData['infaq']) !!},
                        borderColor: '#3b82f6', // blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Sodaqoh',
                        data: {!! json_encode($chartData['sodaqoh']) !!},
                        borderColor: '#eab308', // yellow-500
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
