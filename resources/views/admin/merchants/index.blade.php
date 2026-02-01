@extends('layouts.admin')

@section('title', 'Kelola Merchants')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola Merchants</h2>
            <p class="text-gray-600">Merchant partner untuk voucher Ramadhan</p>
        </div>
        <a href="{{ route('admin.merchants.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Merchant
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vouchers</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Offers</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($merchants as $merchant)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $merchant->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600">{{ $merchant->slug }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $merchant->merchant_vouchers_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $merchant->offers_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($merchant->is_active)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.merchants.edit', $merchant) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.merchants.destroy', $merchant) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus merchant ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Belum ada merchant. <a href="{{ route('admin.merchants.create') }}" class="text-blue-600 hover:text-blue-800">Tambah merchant pertama</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($merchants->hasPages())
        <div class="mt-4">{{ $merchants->links() }}</div>
    @endif
</div>
@endsection
