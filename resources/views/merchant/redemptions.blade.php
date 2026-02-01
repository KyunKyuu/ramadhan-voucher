@extends('layouts.merchant')

@section('title', 'Riwayat Redemption')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Riwayat Redemption</h2>
        <p class="text-gray-600">Daftar voucher yang sudah diredeem</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('merchant.redemptions') }}" class="flex space-x-2">
            <input 
                type="date" 
                name="date" 
                value="{{ request('date') }}"
                class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Filter
            </button>
            @if(request('date'))
                <a href="{{ route('merchant.redemptions') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Redemptions List -->
    <div class="space-y-3">
        @forelse($redemptions as $redemption)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900">{{ $redemption->initialVoucher->claim->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600">{{ $redemption->initialVoucher->claim->email ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                        Redeemed
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">Kode Voucher</p>
                        <p class="font-mono font-semibold text-gray-900">{{ $redemption->code }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">PIC</p>
                        <p class="text-gray-900">{{ $redemption->initialVoucher->pic->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Waktu Redeem</p>
                        <p class="text-gray-900">{{ $redemption->redeemed_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Oleh</p>
                        <p class="text-gray-900">{{ $redemption->redeemedBy->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                @if(request('date'))
                    Tidak ada redemption pada tanggal {{ request('date') }}
                @else
                    Belum ada redemption
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($redemptions->hasPages())
        <div class="mt-4">
            {{ $redemptions->links() }}
        </div>
    @endif
</div>
@endsection
