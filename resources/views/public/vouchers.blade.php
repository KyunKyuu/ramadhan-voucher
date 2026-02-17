@extends('layouts.public')

@section('title', 'Voucher Merchant Anda')

@section('content')
<div class="max-w-2xl mx-auto">
    @php
        $minClaimAmount = (float) config('app.min_claim_amount', 35000);
        $totalDonation = (float) ($claim->zakat_fitrah_amount ?? 0)
            + (float) ($claim->zakat_mal_amount ?? 0)
            + (float) ($claim->infaq_amount ?? 0)
            + (float) ($claim->sodaqoh_amount ?? 0);
        $eligible = $totalDonation >= $minClaimAmount;
    @endphp
    <!-- Success Header -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-6">
        @if($eligible)
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white text-center">
            <div class="text-5xl mb-2">✅</div>
            <h2 class="text-2xl font-bold mb-1">Voucher Berhasil Diklaim!</h2>
            <p class="text-green-100 text-sm">Selamat, {{ $claim->name }}</p>
        </div>

        @else
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 text-white text-center">
                <div class="text-5xl mb-2">!</div>
                <h2 class="text-2xl font-bold mb-1">Penyaluran Tercatat</h2>
                <p class="text-yellow-100 text-sm">Total belum memenuhi minimum untuk voucher merchant.</p>
            </div>
        @endif

        <!-- Claim Info -->
        <div class="p-6 bg-gray-50 border-b">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Nama</p>
                    <p class="font-semibold text-gray-900">{{ $claim->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-semibold text-gray-900">{{ $claim->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500">No. HP</p>
                    <p class="font-semibold text-gray-900">{{ $claim->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Nominal Penyaluran</p>
                    <p class="font-semibold text-gray-900">
                        Zakat Fitrah: Rp {{ number_format($claim->zakat_fitrah_amount ?? 0, 0, ',', '.') }},
                        Zakat Mal: Rp {{ number_format($claim->zakat_mal_amount ?? 0, 0, ',', '.') }},
                        Infaq: Rp {{ number_format($claim->infaq_amount ?? 0, 0, ',', '.') }},
                        Sodaqoh: Rp {{ number_format($claim->sodaqoh_amount ?? 0, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500">Total: Rp {{ number_format($totalDonation, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        @if($eligible)
        <!-- Instructions -->
        <div class="p-6">
            <h3 class="font-semibold text-gray-900 mb-2">📱 Cara Menggunakan:</h3>
            <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                <li>Pilih merchant yang ingin Anda kunjungi</li>
                <li>Klik "Tampilkan QR" untuk menampilkan kode QR</li>
                <li>Tunjukkan QR ke kasir merchant</li>
                <li>Dapatkan diskon sesuai penawaran merchant</li>
            </ol>
        </div>
        @endif
    </div>

    <!-- Merchant Vouchers -->
    <div class="space-y-4">
        <h3 class="text-white font-bold text-xl mb-4">🎁 Voucher Merchant Anda ({{ $merchantVouchers->count() }})</h3>

        @if(!$eligible)
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-xl p-4 text-sm">
                <p class="font-semibold">Belum memenuhi minimum penyaluran.</p>
                <p>Total saat ini: Rp {{ number_format($totalDonation, 0, ',', '.') }}. Minimum: Rp {{ number_format($minClaimAmount, 0, ',', '.') }}.</p>
                <p class="mt-1">Voucher merchant belum tersedia.</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <p class="text-gray-500">Voucher akan muncul setelah total penyaluran minimal Rp {{ number_format($minClaimAmount, 0, ',', '.') }}.</p>
            </div>
        @else
            @forelse($merchantVouchers as $merchantVoucher)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Merchant Header -->
                <div class="p-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 text-lg">{{ $merchantVoucher->merchant->name }}</h4>
                            @if($merchantVoucher->merchant->offer)
                                <p class="text-sm text-purple-600 font-semibold mt-1">
                                    🎉 {{ $merchantVoucher->merchant->offer->title }}
                                </p>
                            @endif
                        </div>
                        <div>
                            @if($merchantVoucher->status === 'ACTIVE')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    ✓ Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    ✓ Terpakai
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Voucher Details -->
                <div class="p-4">
                    @if($merchantVoucher->merchant->offer)
                        <div class="mb-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-sm text-gray-700">{{ $merchantVoucher->merchant->offer->description }}</p>
                        </div>
                    @endif

                    <!-- QR Button -->
                    @if($merchantVoucher->status === 'ACTIVE')
                        <button 
                            onclick="showQR('{{ $merchantVoucher->code }}', '{{ $merchantVoucher->merchant->name }}', '{{ $merchantVoucher->merchant->voucher_template }}')"
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg"
                        >
                            📱 Tampilkan QR Code
                        </button>
                        
                        <button 
                            onclick="openDetailModal({{ json_encode($merchantVoucher->merchant) }})"
                            class="w-full mt-2 bg-white text-purple-600 font-semibold py-3 rounded-lg border border-purple-200 hover:bg-purple-50 transition-all duration-200"
                        >
                            ℹ️ Lihat Detail Merchant
                        </button>
                    @else
                        <div class="text-center py-3 bg-gray-100 rounded-lg">
                            <p class="text-sm text-gray-600">Voucher sudah digunakan pada {{ $merchantVoucher->redeemed_at?->format('d M Y H:i') }}</p>
                        </div>
                    @endif

                    <!-- Voucher Code -->
                    <div class="mt-3 text-center">
                        <p class="text-xs text-gray-500">Kode Voucher</p>
                        <p class="font-mono text-sm font-bold text-gray-900">{{ $merchantVoucher->code }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <p class="text-gray-500">Tidak ada voucher merchant tersedia.</p>
            </div>
            @endforelse
        @endif
    </div>

    <!-- Save Link Info -->
    <div class="mt-6 bg-white/10 backdrop-blur-md rounded-xl p-4 text-white text-center">
        <p class="text-sm mb-2">💡 <strong>Simpan halaman ini!</strong></p>
        <p class="text-xs text-white/80">Bookmark atau screenshot URL ini untuk akses voucher kapan saja</p>
        <div class="mt-2 p-2 bg-white/20 rounded-lg">
            <p class="text-xs font-mono break-all">{{ url()->current() }}</p>
        </div>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" onclick="closeQR()">
    <div class="bg-white rounded-2xl p-4 max-w-lg w-full text-center relative" onclick="event.stopPropagation()">
        <!-- Voucher Container -->
        <div id="voucherContainer" class="relative w-full aspect-[2/1] bg-contain bg-center bg-no-repeat rounded-xl shadow-lg mb-4" style="background-color: #f3f4f6;">
            <!-- QR Position: Right Box (Adjusted based on standard templates) -->
            <div id="qrCodeContainer" class="absolute right-[8%] top-1/2 -translate-y-1/2 bg-white p-2 rounded-lg"></div>
        </div>
        
        <h3 id="merchantName" class="text-xl font-bold text-gray-900 mb-2"></h3>
        <p id="voucherCode" class="font-mono text-lg font-bold text-gray-900"></p>
        <p class="text-sm text-gray-500 mt-2">Tunjukkan QR ini ke kasir</p>
        
        <button 
            onclick="closeQR()"
            class="mt-6 w-full bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-300 transition-all"
        >
            Tutup
        </button>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" onclick="closeDetailModal()">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto relative" onclick="event.stopPropagation()">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span id="detailAddress"></span>
                    </p>
                    <a href="#" target="_blank" rel="noopener noreferrer" id="detailGoogleMaps" class="text-sm text-blue-600 hover:underline mt-1 hidden">
                        lihat di googgle maps
                    </a>
                    <a href="#" target="_blank" id="detailWebsite" class="text-sm text-blue-600 hover:underline flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <span id="detailWebsiteText"></span>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" id="detailWhatsappButton" class="inline-flex items-center justify-center mt-3 px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 hidden">
                        WA Hubungi Admin
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
                <div class="max-h-60 overflow-y-auto pr-2">
                    <p id="detailDescription" class="text-gray-600 whitespace-pre-line text-sm leading-relaxed"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    let currentQR = null;

    function showQR(code, merchantName, template) {
        // Clear previous QR
        const container = document.getElementById('qrCodeContainer');
        container.innerHTML = '';
        
        // Set background image
        const voucherContainer = document.getElementById('voucherContainer');
        const bgUrl = "{{ asset('images/voucher/') }}/" + (template || 'baju.jpeg') + "?v={{ time() }}";
        voucherContainer.style.backgroundImage = `url('${bgUrl}')`;

        // Generate new QR
        currentQR = new QRCode(container, {
            text: code,
            width: 100, // Adjusted size for the box
            height: 100,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Update modal content
        document.getElementById('merchantName').textContent = merchantName;
        document.getElementById('voucherCode').textContent = code;

        // Show modal
        document.getElementById('qrModal').classList.remove('hidden');
    }

    function closeQR() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    function ensureHttpPrefix(url) {
        if (!url) {
            return '';
        }
        return url.startsWith('http://') || url.startsWith('https://')
            ? url
            : 'https://' + url;
    }

    function normalizeWhatsappNumber(rawPhone) {
        if (!rawPhone) {
            return '';
        }

        let digits = String(rawPhone).replace(/\D/g, '');
        if (!digits) {
            return '';
        }

        if (digits.startsWith('0')) {
            digits = '62' + digits.slice(1);
        } else if (digits.startsWith('8')) {
            digits = '62' + digits;
        }

        return digits;
    }

    function openDetailModal(merchant) {
        // Basic Info
        document.getElementById('detailMerchantName').textContent = merchant.name;
        document.getElementById('detailName').textContent = merchant.name;
        document.getElementById('detailLogo').src = merchant.logo_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(merchant.name);
        
        // Address
        const addressEl = document.getElementById('detailAddress');
        const addressContainer = document.getElementById('detailAddressContainer');
        if (merchant.address) {
            addressEl.textContent = merchant.address;
            addressContainer.classList.remove('hidden');
            addressContainer.classList.add('flex');
        } else {
            addressContainer.classList.add('hidden');
            addressContainer.classList.remove('flex');
        }

        // Google Maps
        const googleMapsEl = document.getElementById('detailGoogleMaps');
        if (merchant.google_maps_link) {
            googleMapsEl.href = ensureHttpPrefix(merchant.google_maps_link);
            googleMapsEl.classList.remove('hidden');
        } else {
            googleMapsEl.classList.add('hidden');
        }

        // Website
        const websiteEl = document.getElementById('detailWebsite');
        if (merchant.website) {
            websiteEl.href = ensureHttpPrefix(merchant.website);
            document.getElementById('detailWebsiteText').textContent = merchant.website;
            websiteEl.classList.remove('hidden');
            websiteEl.classList.add('flex');
        } else {
            websiteEl.classList.add('hidden');
            websiteEl.classList.remove('flex');
        }

        // WhatsApp Admin
        const whatsappEl = document.getElementById('detailWhatsappButton');
        const waNumber = normalizeWhatsappNumber(merchant.admin_phone);
        if (waNumber) {
            const waText = encodeURIComponent(`Halo admin ${merchant.name}, saya ingin tanya terkait voucher merchant.`);
            whatsappEl.href = `https://wa.me/${waNumber}?text=${waText}`;
            whatsappEl.classList.remove('hidden');
        } else {
            whatsappEl.classList.add('hidden');
        }

        // Offer Description
        const descEl = document.getElementById('detailDescription');
        if (merchant.offer && merchant.offer.description) {
            descEl.textContent = merchant.offer.description;
        } else {
            descEl.textContent = 'Tidak ada deskripsi tersedia.';
        }

        // Images Carousel
        const imagesContainer = document.getElementById('imagesContainer');
        const carousel = document.getElementById('carouselWrapper');
        carousel.innerHTML = ''; // Clear previous

        if (merchant.offer && merchant.offer.images && merchant.offer.images.length > 0) {
            imagesContainer.classList.remove('hidden');
            merchant.offer.images.forEach(image => {
                const imgDiv = document.createElement('div');
                imgDiv.className = 'flex-shrink-0 w-64 h-48 snap-center rounded-lg overflow-hidden border border-gray-200';
                imgDiv.innerHTML = `<img src="{{ asset('') }}${image.path}" alt="Product" class="w-full h-full object-cover">`;
                carousel.appendChild(imgDiv);
            });
        } else {
            imagesContainer.classList.add('hidden');
        }

        document.getElementById('detailModal').classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQR();
            closeDetailModal();
        }
    });
</script>
@endsection
