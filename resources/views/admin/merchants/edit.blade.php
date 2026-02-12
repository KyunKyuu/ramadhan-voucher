@extends('layouts.admin')

@section('title', 'Edit Merchant')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Edit Merchant</h2>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.merchants.update', $merchant) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Merchant <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $merchant->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $merchant->slug) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                        placeholder="Otomatis dari nama jika kosong">
                    @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $merchant->email) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" required>
                    <p class="mt-1 text-xs text-gray-500">Email untuk login merchant</p>
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password Baru
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password. Minimal 8 karakter jika diisi.</p>
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Toko
                    </label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                        placeholder="Alamat lengkap lokasi toko/merchant">{{ old('address', $merchant->address) }}</textarea>
                    @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">
                        Social Media / Website
                    </label>
                    <input type="text" name="website" id="website" value="{{ old('website', $merchant->website) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror"
                        placeholder="Link Instagram/TikTok/Website">
                    @error('website')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="border-t pt-4 mt-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Penawaran Spesial (Default)</h3>
                    @php
                        $offer = $merchant->offer;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2">
                             <label for="offer_title" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Menu / Artikel
                            </label>
                            <input type="text" name="offer_title" id="offer_title" value="{{ old('offer_title', $offer?->title) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('offer_title') border-red-500 @enderror"
                                placeholder="Contoh: Diskon All Item, Potongan Harga Kopi">
                            @error('offer_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="col-span-2">
                             <label for="offer_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Deskripsi Menu / Artikel
                            </label>
                            <textarea name="offer_description" id="offer_description" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('offer_description') border-red-500 @enderror"
                                placeholder="Jelaskan detail menu atau penawaran anda">{{ old('offer_description', $offer?->description) }}</textarea>
                            @error('offer_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk Saat Ini</label>
                            @if($offer && $offer->images->count() > 0)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    @foreach($offer->images as $image)
                                        <div class="relative group">
                                            <img src="{{ asset($image->path) }}" alt="Product Image" class="w-full h-32 object-cover rounded-lg border">
                                            <!-- Future: Add delete button here -->
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic mb-4">Belum ada foto produk.</p>
                            @endif

                             <label for="product_images" class="block text-sm font-medium text-gray-700 mb-1">
                                Tambah Foto Produk (Bisa lebih dari satu)
                            </label>
                            <input type="file" name="product_images[]" id="product_images" multiple accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('product_images.*') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB per file. Foto baru akan ditambahkan.</p>
                            @error('product_images.*')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipe Diskon
                            </label>
                            <select name="discount_type" id="discount_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="PERCENT" {{ old('discount_type', $offer?->discount_type) == 'PERCENT' ? 'selected' : '' }}>Persen (%)</option>
                                <option value="FIXED" {{ old('discount_type', $offer?->discount_type) == 'FIXED' ? 'selected' : '' }}>Nominal (Rp)</option>
                            </select>
                        </div>

                        <div>
                            <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">
                                Nilai Diskon
                            </label>
                            <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value', $offer?->discount_value ? (float)$offer->discount_value : '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('discount_value') border-red-500 @enderror"
                                placeholder="0">
                            @error('discount_value')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="border-t pt-4 mt-4">
                    <label for="logo_url" class="block text-sm font-medium text-gray-700 mb-1">Logo URL</label>
                    <input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url', $merchant->logo_url) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('logo_url') border-red-500 @enderror"
                        placeholder="https://example.com/logo.png">
                    @error('logo_url')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Desain Voucher</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($templates as $template)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="voucher_template" value="{{ $template }}" 
                                class="peer sr-only" 
                                {{ old('voucher_template', $merchant->voucher_template) == $template ? 'checked' : '' }}>
                            
                            <div class="border-2 border-transparent peer-checked:border-blue-500 rounded-lg overflow-hidden hover:opacity-90 transition-all">
                                <img src="{{ asset('images/voucher/' . $template) }}" alt="{{ $template }}" class="w-full h-auto object-cover">
                            </div>
                            
                            <div class="absolute top-2 right-2 bg-blue-500 text-white p-1 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $merchant->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Merchant Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t">
                <a href="{{ route('admin.merchants.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
