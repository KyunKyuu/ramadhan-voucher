@extends('layouts.admin')

@section('title', 'Detail PIC')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.pics.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900">Detail PIC</h2>
            </div>
            <p class="text-gray-600 mt-1">Informasi lengkap dan daftar voucher</p>
        </div>
        <a href="{{ route('admin.pics.edit', $pic) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Edit PIC
        </a>
    </div>

    <!-- PIC Info Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Nama PIC</label>
                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $pic->name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Kode PIC</label>
                <p class="mt-1 text-lg font-mono text-gray-900">{{ $pic->code ?? '-' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <div class="mt-1">
                    @if($pic->is_active)
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    @else
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Vouchers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Voucher</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">🎫</span>
                </div>
            </div>
        </div>

        <!-- Assigned Vouchers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Belum Diklaim</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['assigned'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">⏳</span>
                </div>
            </div>
        </div>

        <!-- Claimed Vouchers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sudah Diklaim</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['claimed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
            </div>
        </div>
        <!-- Commission Claimed -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Komisi Terklaim</p>
                    <p class="text-2xl font-bold text-teal-600">Rp {{ number_format($stats['commission_claimed'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Pending: Rp {{ number_format($stats['commission_pending'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                    <span class="text-sm font-semibold text-teal-700">Rp</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Vouchers Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daftar Voucher</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Voucher</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Komisi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Claimer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email Claimer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Klaim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pic->initialVouchers as $voucher)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-medium text-gray-900">{{ $voucher->code }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-600">{{ $voucher->batch->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($voucher->status === 'ASSIGNED')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Belum Diklaim
                                    </span>
                                @elseif($voucher->status === 'CLAIMED')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Sudah Diklaim
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $voucher->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($voucher->commission_amount > 0)
                                    <span class="text-sm font-medium text-gray-900">Rp {{ number_format($voucher->commission_amount, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($voucher->claim)
                                    <span class="text-sm font-medium text-gray-900">{{ $voucher->claim->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($voucher->claim)
                                    <span class="text-sm text-gray-600">{{ $voucher->claim->email }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($voucher->claimed_at)
                                    <span class="text-sm text-gray-600">{{ $voucher->claimed_at->format('d M Y, H:i') }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Belum ada voucher yang di-assign ke PIC ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
