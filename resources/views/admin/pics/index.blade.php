@extends('layouts.admin')

@section('title', 'Kelola PICs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Kelola PICs</h2>
            <p class="text-gray-600">Person In Charge untuk distribusi voucher</p>
        </div>
        <a href="{{ route('admin.pics.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah PIC
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vouchers</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pics as $pic)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $pic->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-sm text-gray-600">{{ $pic->code ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $pic->initial_vouchers_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($pic->is_active)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.pics.show', $pic) }}" class="text-teal-600 hover:text-teal-800 font-medium">Detail</a>
                            <a href="{{ route('admin.pics.edit', $pic) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.pics.destroy', $pic) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus PIC ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Belum ada PIC. <a href="{{ route('admin.pics.create') }}" class="text-blue-600 hover:text-blue-800">Tambah PIC pertama</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($pics->hasPages())
        <div class="mt-4">
            {{ $pics->links() }}
        </div>
    @endif
</div>
@endsection
