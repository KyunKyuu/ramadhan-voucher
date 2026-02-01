@extends('layouts.admin')

@section('title', 'Redemptions Data')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Redemptions Data</h2>
            <p class="text-gray-600">Daftar semua penukaran voucher</p>
        </div>
        <a href="{{ route('admin.exports.redeems') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            📥 Export CSV
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Redeemed</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_redeemed']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Today</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['today_redeemed']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">This Week</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['this_week_redeemed']) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.redeems.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Merchant</label>
                <select name="merchant_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Merchants</option>
                    @foreach($merchants as $merchant)
                        <option value="{{ $merchant->id }}" {{ request('merchant_id') == $merchant->id ? 'selected' : '' }}>
                            {{ $merchant->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">PIC</label>
                <select name="pic_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">All PICs</option>
                    @foreach($pics as $pic)
                        <option value="{{ $pic->id }}" {{ request('pic_id') == $pic->id ? 'selected' : '' }}>
                            {{ $pic->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer name/email..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">Filter</button>
                <a href="{{ route('admin.redeems.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Redemptions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merchant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voucher Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Redeemed At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Redeemed By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($redemptions as $redemption)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $redemption->initialVoucher->claim->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $redemption->initialVoucher->claim->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $redemption->merchant->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $redemption->code }}</code>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $redemption->initialVoucher->pic->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $redemption->redeemed_at ? $redemption->redeemed_at->format('d M Y H:i') : 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $redemption->redeemedBy->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.redeems.show', $redemption->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">No redemptions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($redemptions->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $redemptions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
