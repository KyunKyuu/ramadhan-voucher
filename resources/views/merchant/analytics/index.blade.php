@extends('layouts.merchant')

@section('title', 'Analytics')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Analytics</h2>
        <p class="text-gray-600">Performance merchant Anda</p>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('merchant.analytics') }}" class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
        </form>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-600 mb-1">Hari Ini</div>
            <div class="text-2xl font-bold text-blue-600">{{ $kpis['redeemed_today'] }}</div>
            <div class="text-xs text-gray-500">Redeemed</div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-600 mb-1">Total</div>
            <div class="text-2xl font-bold text-green-600">{{ $kpis['total_redeemed'] }}</div>
            <div class="text-xs text-gray-500">Redeemed</div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-600 mb-1">Unique Users</div>
            <div class="text-2xl font-bold text-purple-600">{{ $kpis['unique_users'] }}</div>
            <div class="text-xs text-gray-500">Customers</div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-xs text-gray-600 mb-1">In Range</div>
            <div class="text-2xl font-bold text-orange-600">{{ $kpis['redeemed_in_range'] }}</div>
            <div class="text-xs text-gray-500">Redeemed</div>
        </div>
    </div>

    <!-- Daily Trend -->
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-gray-900 mb-3">Trend 7 Hari Terakhir</h3>
        <div class="space-y-2">
            @foreach($dailyTrend as $day)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($day['date'])->format('D, d M') }}</span>
                    <span class="text-sm font-semibold text-purple-600">{{ $day['redeems'] }} redeems</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top PICs -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-900">Top PICs (Pembawa Customer)</h3>
        </div>
        <div class="divide-y">
            @forelse($topPics as $pic)
                <div class="p-4 flex justify-between items-center">
                    <span class="font-medium text-gray-900">{{ $pic->name }}</span>
                    <span class="text-lg font-bold text-green-600">{{ $pic->redeems_count }}</span>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">Belum ada data</div>
            @endforelse
        </div>
    </div>

    <!-- Top Staff -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-900">Top Staff (Redeemer)</h3>
        </div>
        <div class="divide-y">
            @forelse($topStaff as $staff)
                <div class="p-4 flex justify-between items-center">
                    <span class="font-medium text-gray-900">{{ $staff->redeemedBy->name ?? 'N/A' }}</span>
                    <span class="text-lg font-bold text-blue-600">{{ $staff->redeems_count }}</span>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">Belum ada data</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
