@extends('layouts.admin')

@section('title', 'Claim Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Claim Details</h2>
            <p class="text-gray-600">Detail informasi klaim voucher</p>
        </div>
        <a href="{{ route('admin.claims.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
            ← Back to Claims
        </a>
    </div>

    <!-- Claim Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Claim Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Customer Name</label>
                <p class="text-lg font-semibold text-gray-900">{{ $claim->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Email</label>
                <p class="text-lg text-gray-900">{{ $claim->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">No. HP</label>
                <p class="text-lg text-gray-900">{{ $claim->phone ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Voucher Code</label>
                <p class="text-lg font-mono bg-gray-100 px-3 py-1 rounded inline-block">{{ $claim->initialVoucher->code ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Public Token</label>
                <p class="text-sm font-mono bg-gray-100 px-3 py-1 rounded inline-block">{{ $claim->public_token }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">PIC</label>
                <p class="text-lg text-gray-900">{{ $claim->initialVoucher->pic->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $claim->initialVoucher->pic->code ?? '' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Batch</label>
                <p class="text-lg text-gray-900">{{ $claim->initialVoucher->batch->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Claimed At</label>
                <p class="text-lg text-gray-900">{{ $claim->created_at->format('d M Y H:i:s') }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Public Link</label>
                <a href="{{ route('public.vouchers', $claim->public_token) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                    View Public Page →
                </a>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-600">Nominal Penyaluran</label>
                <p class="text-lg text-gray-900">
                    Zakat Fitrah: Rp {{ number_format($claim->zakat_fitrah_amount ?? 0, 0, ',', '.') }},
                    Infaq: Rp {{ number_format($claim->infaq_amount ?? 0, 0, ',', '.') }},
                    Sodaqoh: Rp {{ number_format($claim->sodaqoh_amount ?? 0, 0, ',', '.') }}
                </p>
                </p>
            </div>
            <div class="md:col-span-2 border-t pt-4 mt-2">
                <label class="block text-sm font-medium text-gray-600 mb-2">Status Verifikasi Dana</label>
                <div class="flex items-center space-x-4">
                    @if($claim->verification_status == 'VERIFIED')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Termasuk dalam setoran terverifikasi
                        </span>
                        @if($claim->verified_at)
                            <span class="text-sm text-gray-500">pada {{ $claim->verified_at->format('d M Y H:i') }}</span>
                        @endif
                    @elseif($claim->verification_status == 'ANOMALY')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Anomali: {{ $claim->verification_note }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Belum Diverifikasi
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Merchant Vouchers -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Merchant Vouchers ({{ $claim->merchantVouchers->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merchant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voucher Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Offer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Redeemed At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($claim->merchantVouchers as $voucher)
                        <tr>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $voucher->merchant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $voucher->code }}</code>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $voucher->merchant->offer->title ?? 'No offer' }}
                                <span class="text-gray-500">({{ $voucher->merchant->offer->formatted_discount ?? '' }})</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($voucher->status === 'REDEEMED')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Redeemed
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $voucher->redeemed_at ? $voucher->redeemed_at->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No merchant vouchers</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
