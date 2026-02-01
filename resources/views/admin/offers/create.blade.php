@extends('layouts.admin')

@section('title', isset($offer) ? 'Edit Offer' : 'Tambah Offer')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ isset($offer) ? 'Edit Offer' : 'Tambah Offer Baru' }}</h2>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($offer) ? route('admin.offers.update', $offer) : route('admin.offers.store') }}" method="POST">
            @csrf
            @if(isset($offer))
                @method('PUT')
            @endif

            <div class="space-y-4">
                <div>
                    <label for="merchant_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Merchant <span class="text-red-500">*</span>
                    </label>
                    <select name="merchant_id" id="merchant_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('merchant_id') border-red-500 @enderror">
                        <option value="">Pilih Merchant</option>
                        @foreach($merchants as $merchant)
                            <option value="{{ $merchant->id }}" {{ old('merchant_id', $offer->merchant_id ?? '') == $merchant->id ? 'selected' : '' }}>
                                {{ $merchant->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('merchant_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Judul Offer <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $offer->title ?? '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                        placeholder="Contoh: Diskon 20% untuk semua menu">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipe Diskon <span class="text-red-500">*</span>
                        </label>
                        <select name="discount_type" id="discount_type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('discount_type') border-red-500 @enderror">
                            <option value="PERCENT" {{ old('discount_type', $offer->discount_type ?? '') == 'PERCENT' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="AMOUNT" {{ old('discount_type', $offer->discount_type ?? '') == 'AMOUNT' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                        @error('discount_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">
                            Nilai Diskon <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value', $offer->discount_value ?? '') }}" 
                            step="0.01" min="0" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('discount_value') border-red-500 @enderror">
                        @error('discount_value')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Syarat dan ketentuan offer">{{ old('description', $offer->description ?? '') }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $offer->is_active ?? true) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Offer Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('admin.offers.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    {{ isset($offer) ? 'Update' : 'Tambah' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
