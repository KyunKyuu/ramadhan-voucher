@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Analytics & Insights</h2>
            <p class="text-gray-600">Program performance dan statistik</p>
        </div>
        <a href="{{ route('admin.exports.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            📥 Export Data
        </a>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('admin.analytics') }}" class="flex items-end space-x-3">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Filter</button>
        </form>
    </div>

    <!-- Global KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Generated</div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($kpis['total_generated']) }}</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Claimed</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($kpis['total_claimed']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Claim Rate: {{ $kpis['claim_rate'] }}%</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Redeemed</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($kpis['total_redeemed']) }}</div>
            <div class="text-xs text-gray-500 mt-1">Redeem Rate: {{ $kpis['redeem_rate'] }}%</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">In Date Range</div>
            <div class="text-lg font-bold text-blue-600">{{ number_format($kpis['claims_in_range']) }} claims</div>
            <div class="text-lg font-bold text-orange-600">{{ number_format($kpis['redeems_in_range']) }} redeems</div>
        </div>
    </div>

    <!-- Daily Trend -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Trend (Last 7 Days)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Claims</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Redeems</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($dailyTrend as $day)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ \Carbon\Carbon::parse($day['date'])->format('D, d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-green-600">{{ $day['claims'] }}</td>
                            <td class="px-4 py-3 text-sm text-right font-semibold text-purple-600">{{ $day['redeems'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top PICs & Merchant Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top PICs by Claims -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Top PICs by Claims</h3>
            </div>
            <div class="divide-y">
                @forelse($topPicsByClaims as $pic)
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">{{ $pic->name }}</p>
                            <p class="text-xs text-gray-500">{{ $pic->code ?? 'No code' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-600">{{ $pic->initial_vouchers_count }}</p>
                            <p class="text-xs text-gray-500">claims</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No data</div>
                @endforelse
            </div>
        </div>

        <!-- Top PICs by Redeems -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Top PICs by Redeems</h3>
            </div>
            <div class="divide-y">
                @forelse($topPicsByRedeems as $pic)
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">{{ $pic->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-purple-600">{{ $pic->redeems_count }}</p>
                            <p class="text-xs text-gray-500">redeems</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No data</div>
                @endforelse
            </div>
        </div>

        <!-- Top PICs by Commission -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Top PICs by Commission</h3>
            </div>
            <div class="divide-y">
                @forelse($topPicsByCommission as $pic)
                    <div class="p-4 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">{{ $pic->name }}</p>
                            <p class="text-xs text-gray-500">{{ $pic->claims_count }} claims</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-teal-600">Rp {{ number_format($pic->commission_total ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No data</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Merchant Stats -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Redemption by Merchant</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Merchant</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Vouchers</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Redeemed</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($merchantStats as $merchant)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $merchant->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900">{{ number_format($merchant->total_vouchers) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-purple-600">{{ number_format($merchant->redeemed_vouchers) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $merchant->redeem_rate >= 50 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $merchant->redeem_rate }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
