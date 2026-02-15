@extends('layouts.admin')

@section('title', 'Claims Data')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Claims Data</h2>
            <p class="text-gray-600">Daftar semua klaim voucher</p>
        </div>
        <a href="{{ route('admin.exports.claims') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            📥 Export CSV
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Claims</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_claims']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Today</div>
            <div class="text-3xl font-bold text-blue-600">{{ number_format($stats['today_claims']) }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">This Week</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['this_week_claims']) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.claims.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
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
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">Filter</button>
                <a href="{{ route('admin.claims.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 text-sm">Reset</a>
            </div>
        </form>
    </div>

    <!-- Claims Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Voucher Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batch</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Donation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fund Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Claimed At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($claims as $claim)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $claim->name }}</div>
                                <div class="text-sm text-gray-500">{{ $claim->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $claim->initialVoucher->code ?? 'N/A' }}</code>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $claim->initialVoucher->pic->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $claim->initialVoucher->batch->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                Rp {{ number_format($claim->total_donation_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($claim->verification_status == 'VERIFIED')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Verified
                                    </span>
                                @elseif($claim->verification_status == 'ANOMALY')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Anomaly
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $claim->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.claims.show', $claim->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No claims found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($claims->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $claims->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
