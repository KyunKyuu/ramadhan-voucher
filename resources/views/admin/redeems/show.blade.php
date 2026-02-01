@extends('layouts.admin')

@section('title', 'Redemption Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Redemption Details</h2>
            <p class="text-gray-600">Detail informasi penukaran voucher</p>
        </div>
        <a href="{{ route('admin.redeems.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
            ← Back to Redemptions
        </a>
    </div>

    <!-- Redemption Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Redemption Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Merchant Voucher Code</label>
                <p class="text-lg font-mono bg-gray-100 px-3 py-1 rounded inline-block">{{ $redemption->code }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Status</label>
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                    Redeemed
                </span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Merchant</label>
                <p class="text-lg font-semibold text-gray-900">{{ $redemption->merchant->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Offer</label>
                <p class="text-lg text-gray-900">{{ $redemption->merchant->offer->title ?? 'No offer' }}</p>
                <p class="text-sm text-gray-500">{{ $redemption->merchant->offer->formatted_discount ?? '' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Redeemed At</label>
                <p class="text-lg text-gray-900">{{ $redemption->redeemed_at ? $redemption->redeemed_at->format('d M Y H:i:s') : 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Redeemed By (Staff)</label>
                <p class="text-lg text-gray-900">{{ $redemption->redeemedBy->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $redemption->redeemedBy->email ?? '' }}</p>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600">Name</label>
                <p class="text-lg font-semibold text-gray-900">{{ $redemption->initialVoucher->claim->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Email</label>
                <p class="text-lg text-gray-900">{{ $redemption->initialVoucher->claim->email ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Initial Voucher Code</label>
                <p class="text-lg font-mono bg-gray-100 px-3 py-1 rounded inline-block">{{ $redemption->initialVoucher->code ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">PIC</label>
                <p class="text-lg text-gray-900">{{ $redemption->initialVoucher->pic->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $redemption->initialVoucher->pic->code ?? '' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Batch</label>
                <p class="text-lg text-gray-900">{{ $redemption->initialVoucher->batch->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Claimed At</label>
                <p class="text-lg text-gray-900">{{ $redemption->initialVoucher->claim->created_at ? $redemption->initialVoucher->claim->created_at->format('d M Y H:i') : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Offer Details -->
    @if($redemption->merchant->offer)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Offer Details</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Title</label>
                    <p class="text-lg text-gray-900">{{ $redemption->merchant->offer->title }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Discount</label>
                    <p class="text-2xl font-bold text-green-600">{{ $redemption->merchant->offer->formatted_discount }}</p>
                </div>
                @if($redemption->merchant->offer->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Description</label>
                        <p class="text-gray-900">{{ $redemption->merchant->offer->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
