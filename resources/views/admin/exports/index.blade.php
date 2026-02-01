@extends('layouts.admin')

@section('title', 'Export Data')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Export Center</h2>
        <p class="text-gray-600">Download data dalam format CSV</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Claims</div>
            <div class="text-3xl font-bold text-green-600">{{ number_format($stats['total_claims']) }}</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Redeems</div>
            <div class="text-3xl font-bold text-purple-600">{{ number_format($stats['total_redeems']) }}</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm text-gray-600 mb-1">Total Vouchers</div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_vouchers']) }}</div>
        </div>
    </div>

    <!-- Export Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Claims Export -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <div class="text-4xl mb-2">📊</div>
                <h3 class="text-lg font-semibold text-gray-900">Claims Data</h3>
                <p class="text-sm text-gray-600 mt-1">Export semua data klaim voucher</p>
            </div>

            <form action="{{ route('admin.exports.claims') }}" method="GET" class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">From Date (Optional)</label>
                    <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">To Date (Optional)</label>
                    <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-semibold">
                    📥 Download Claims CSV
                </button>
            </form>

            <div class="mt-4 text-xs text-gray-500">
                <p class="font-semibold mb-1">Includes:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Voucher code</li>
                    <li>Customer name & email</li>
                    <li>PIC & Batch info</li>
                    <li>Claim timestamp</li>
                </ul>
            </div>
        </div>

        <!-- Redeems Export -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <div class="text-4xl mb-2">🎁</div>
                <h3 class="text-lg font-semibold text-gray-900">Redemptions Data</h3>
                <p class="text-sm text-gray-600 mt-1">Export semua data penukaran voucher</p>
            </div>

            <form action="{{ route('admin.exports.redeems') }}" method="GET" class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">From Date (Optional)</label>
                    <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">To Date (Optional)</label>
                    <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 font-semibold">
                    📥 Download Redeems CSV
                </button>
            </form>

            <div class="mt-4 text-xs text-gray-500">
                <p class="font-semibold mb-1">Includes:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Merchant voucher code</li>
                    <li>Merchant name</li>
                    <li>Customer & PIC info</li>
                    <li>Redeem timestamp</li>
                    <li>Redeemed by (staff)</li>
                </ul>
            </div>
        </div>

        <!-- Vouchers Export -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <div class="text-4xl mb-2">🎫</div>
                <h3 class="text-lg font-semibold text-gray-900">Vouchers Data</h3>
                <p class="text-sm text-gray-600 mt-1">Export semua data voucher awal</p>
            </div>

            <form action="{{ route('admin.exports.vouchers') }}" method="GET" class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Status (Optional)</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">All Status</option>
                        <option value="UNASSIGNED">Unassigned</option>
                        <option value="ASSIGNED">Assigned</option>
                        <option value="CLAIMED">Claimed</option>
                        <option value="VOID">Void</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-semibold mt-12">
                    📥 Download Vouchers CSV
                </button>
            </form>

            <div class="mt-4 text-xs text-gray-500">
                <p class="font-semibold mb-1">Includes:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Voucher code</li>
                    <li>Batch & PIC info</li>
                    <li>Status</li>
                    <li>Claimed by (if claimed)</li>
                    <li>Timestamps</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <div class="text-2xl mr-3">ℹ️</div>
            <div>
                <h4 class="font-semibold text-blue-900 mb-1">Export Information</h4>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• Files akan didownload dalam format CSV (Excel compatible)</li>
                    <li>• Filter tanggal bersifat opsional (kosongkan untuk export semua data)</li>
                    <li>• Nama file akan include timestamp untuk tracking</li>
                    <li>• Data di-export real-time dari database</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
