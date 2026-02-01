@extends('layouts.public')

@section('title', 'Voucher Merchant Anda')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Success Header -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 text-white text-center">
            <div class="text-5xl mb-2">✅</div>
            <h2 class="text-2xl font-bold mb-1">Voucher Berhasil Diklaim!</h2>
            <p class="text-green-100 text-sm">Selamat, {{ $claim->name }}</p>
        </div>

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
            </div>
        </div>

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
    </div>

    <!-- Merchant Vouchers -->
    <div class="space-y-4">
        <h3 class="text-white font-bold text-xl mb-4">🎁 Voucher Merchant Anda ({{ $merchantVouchers->count() }})</h3>

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
                            onclick="showQR('{{ $merchantVoucher->code }}', '{{ $merchantVoucher->merchant->name }}')"
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg"
                        >
                            📱 Tampilkan QR Code
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
    <div class="bg-white rounded-2xl p-8 max-w-sm w-full text-center" onclick="event.stopPropagation()">
        <h3 id="merchantName" class="text-xl font-bold text-gray-900 mb-4"></h3>
        <div id="qrCodeContainer" class="bg-white p-4 rounded-xl inline-block"></div>
        <p id="voucherCode" class="mt-4 font-mono text-lg font-bold text-gray-900"></p>
        <p class="text-sm text-gray-500 mt-2">Tunjukkan QR ini ke kasir</p>
        <button 
            onclick="closeQR()"
            class="mt-6 w-full bg-gray-200 text-gray-800 font-semibold py-3 rounded-lg hover:bg-gray-300 transition-all"
        >
            Tutup
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    let currentQR = null;

    function showQR(code, merchantName) {
        // Clear previous QR
        const container = document.getElementById('qrCodeContainer');
        container.innerHTML = '';

        // Generate new QR
        currentQR = new QRCode(container, {
            text: code,
            width: 256,
            height: 256,
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

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeQR();
        }
    });
</script>
@endsection
