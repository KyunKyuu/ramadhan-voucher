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
        <form method="POST" action="{{ route('public.claim.store') }}" enctype="multipart/form-data"
            class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="code" value="{{ $code }}">
            @php($selectedPaymentMethod = old('payment_method', 'cash'))

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
                <select name="pic_id" id="pic_id"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('pic_id') border-red-500 @enderror"
                    required>
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
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama Anda" required>
            </div>

            <!-- Phone Input -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                    No. HP <span class="text-red-500">*</span>
                </label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                    placeholder="Contoh: 081234567890" required>
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="nama@email.com" required>
            </div>

            <!-- Payment Method -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                    Metode Pembayaran <span class="text-red-500">*</span>
                </label>
                <select name="payment_method" id="payment_method"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('payment_method') border-red-500 @enderror"
                    required>
                    <option value="cash" {{ $selectedPaymentMethod === 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="transfer" {{ $selectedPaymentMethod === 'transfer' ? 'selected' : '' }}>Transfer
                    </option>
                </select>
            </div>

            <div id="transferFields" class="space-y-3 {{ $selectedPaymentMethod === 'transfer' ? '' : 'hidden' }}">
                <div>
                    <label for="transfer_destination" class="block text-sm font-medium text-gray-700 mb-1">
                        Transfer ke Mana <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="transfer_destination" id="transfer_destination"
                        value="Blu 090109627811 a.n Ahmad Bustan Djatmadipura"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 focus:ring-0 cursor-not-allowed"
                        readonly>
                </div>
                <div>
                    <label for="transfer_proof" class="block text-sm font-medium text-gray-700 mb-1">
                        Upload Bukti Transfer <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="transfer_proof" id="transfer_proof"
                        accept=".jpg,.jpeg,.png,.pdf,image/*,application/pdf"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('transfer_proof') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF. Maksimal 4MB.</p>
                </div>
            </div>

            <!-- Nominal Penyaluran -->
            <div class="pt-2">
                <p class="text-sm font-medium text-gray-700 mb-2">Nominal Penyaluran</p>
                <div class="space-y-3">
                    <div>
                        <label for="zakat_fitrah_amount" class="block text-xs font-medium text-gray-600 mb-1">
                            Zakat Fitrah (Rp)
                        </label>
                        <input type="number" name="zakat_fitrah_amount" id="zakat_fitrah_amount" min="0" step="1"
                            value="{{ old('zakat_fitrah_amount', 0) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('zakat_fitrah_amount') border-red-500 @enderror"
                            placeholder="0">
                    </div>
                    <div>
                        <label for="zakat_mal_amount" class="block text-xs font-medium text-gray-600 mb-1">
                            Zakat Mal (Rp)
                        </label>
                        <input type="number" name="zakat_mal_amount" id="zakat_mal_amount" min="0" step="1"
                            value="{{ old('zakat_mal_amount', 0) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('zakat_mal_amount') border-red-500 @enderror"
                            placeholder="0">
                    </div>
                    <div>
                        <label for="infaq_amount" class="block text-xs font-medium text-gray-600 mb-1">
                            Infaq (Rp)
                        </label>
                        <input type="number" name="infaq_amount" id="infaq_amount" min="0" step="1"
                            value="{{ old('infaq_amount', 0) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('infaq_amount') border-red-500 @enderror"
                            placeholder="0">
                    </div>
                    <div>
                        <label for="sodaqoh_amount" class="block text-xs font-medium text-gray-600 mb-1">
                            Sodaqoh (Rp)
                        </label>
                        <input type="number" name="sodaqoh_amount" id="sodaqoh_amount" min="0" step="1"
                            value="{{ old('sodaqoh_amount', 0) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('sodaqoh_amount') border-red-500 @enderror"
                            placeholder="0">
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500">
                    Minimum total penyaluran Rp {{ number_format(config('app.min_claim_amount', 35000), 0, ',', '.') }}
                    untuk mendapatkan voucher merchant.
                </p>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-4 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
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

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative">
        <div class="sticky top-0 bg-white p-4 border-b flex justify-between items-center z-10">
            <h3 class="text-xl font-bold text-gray-900" id="detailMerchantName">Detail Merchant</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 space-y-6">
            <!-- Merchant Header -->
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gray-200 rounded-full overflow-hidden flex-shrink-0">
                    <img id="detailLogo" src="" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <h4 class="text-lg font-bold text-gray-900" id="detailName"></h4>
                    <p class="text-sm text-gray-600 flex items-center mt-1" id="detailAddressContainer">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span id="detailAddress"></span>
                    </p>
                    <a href="#" target="_blank" id="detailWebsite"
                        class="text-sm text-blue-600 hover:underline flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <span id="detailWebsiteText"></span>
                    </a>
                </div>
            </div>

            <!-- Product Images Carousel -->
            <div id="imagesContainer" class="hidden">
                <h5 class="font-semibold text-gray-900 mb-3">Foto Produk</h5>
                <div class="relative group">
                    <div id="carouselWrapper" class="flex overflow-x-auto snap-x snap-mandatory space-x-4 pb-4">
                        <!-- Images injected here -->
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h5 class="font-semibold text-gray-900 mb-2">Deskripsi Penawaran</h5>
                <p id="detailDescription" class="text-gray-600 whitespace-pre-line"></p>
            </div>
        </div>
    </div>
</div>

<!-- Select2 for searchable dropdown -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentMethod = document.getElementById('payment_method');
        const transferFields = document.getElementById('transferFields');
        const transferDestination = document.getElementById('transfer_destination');
        const transferProof = document.getElementById('transfer_proof');

        function toggleTransferFields() {
            const isTransfer = paymentMethod && paymentMethod.value === 'transfer';

            if (transferFields) {
                transferFields.classList.toggle('hidden', !isTransfer);
            }

            if (transferDestination) {
                transferDestination.required = isTransfer;
            }

            if (transferProof) {
                transferProof.required = isTransfer;
            }
        }

        if (paymentMethod) {
            paymentMethod.addEventListener('change', toggleTransferFields);
            toggleTransferFields();
        }
    });
</script>

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

@endsection