@extends('layouts.public')

@section('title', 'Klaim Voucher Ramadhan')

@section('content')
<div class="max-w-md mx-auto">
    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 text-white">
            <div class="text-center">
                <div class="text-5xl mb-2">🎁</div>
                <h2 class="text-2xl font-bold mb-1">Klaim Voucher Anda</h2>
                <p class="text-purple-100 text-sm">Dapatkan voucher merchant spesial Ramadhan</p>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('public.claim.store') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="code" value="{{ $code }}">

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- PIC Selection -->
            <div>
                <label for="pic_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Pilih PIC Anda <span class="text-red-500">*</span>
                </label>
                <select 
                    name="pic_id" 
                    id="pic_id" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('pic_id') border-red-500 @enderror"
                    required
                >
                    <option value="">-- Pilih PIC --</option>
                    @foreach($pics as $pic)
                        <option value="{{ $pic->id }}" {{ old('pic_id') == $pic->id ? 'selected' : '' }}>
                            {{ $pic->name }}{{ $pic->code ? ' (' . $pic->code . ')' : '' }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Pilih PIC yang memberikan voucher ini kepada Anda</p>
            </div>

            <!-- Name Input -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama Anda"
                    required
                >
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="nama@email.com"
                    required
                >
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-4 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
            >
                🎁 Dapatkan Voucher Sekarang
            </button>

            <!-- Info Text -->
            <p class="text-xs text-gray-500 text-center">
                Dengan mengklaim, Anda akan mendapatkan voucher dari semua merchant yang berpartisipasi
            </p>
        </form>
    </div>

    <!-- Voucher Code Display -->
    <div class="mt-4 text-center">
        <p class="text-white/80 text-sm">Kode Voucher</p>
        <p class="text-white font-mono text-lg font-bold tracking-wider">{{ $code }}</p>
    </div>
</div>

<!-- Select2 for searchable dropdown -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container--default .select2-selection--single {
        height: 48px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px;
        padding-left: 4px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px;
    }
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #9333ea;
        box-shadow: 0 0 0 2px rgba(147, 51, 234, 0.2);
    }
</style>

<script>
    $(document).ready(function() {
        $('#pic_id').select2({
            placeholder: '-- Pilih PIC --',
            allowClear: false,
            width: '100%'
        });
    });
</script>
@endsection
