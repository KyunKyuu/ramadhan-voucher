@extends('layouts.admin')

@section('title', isset($pic) ? 'Edit PIC' : 'Tambah PIC')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ isset($pic) ? 'Edit PIC' : 'Tambah PIC Baru' }}</h2>
        <p class="text-gray-600">{{ isset($pic) ? 'Update informasi PIC' : 'Tambahkan PIC untuk distribusi voucher' }}</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($pic) ? route('admin.pics.update', $pic) : route('admin.pics.store') }}" method="POST">
            @csrf
            @if(isset($pic))
                @method('PUT')
            @endif

            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $pic->name ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">
                        Kode PIC
                    </label>
                    <input 
                        type="text" 
                        name="code" 
                        id="code" 
                        value="{{ old('code', $pic->code ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('code') border-red-500 @enderror"
                        placeholder="Contoh: PIC001"
                    >
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Opsional. Kode unik untuk identifikasi PIC.</p>
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $pic->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <span class="ml-2 text-sm text-gray-700">PIC Aktif</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">PIC yang tidak aktif tidak bisa menerima assignment voucher baru.</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('admin.pics.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ isset($pic) ? 'Update PIC' : 'Tambah PIC' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
