@extends('layouts.merchant')

@section('title', 'Scan Voucher')

@section('head')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Scan Voucher</h2>
        <p class="text-gray-600">Scan QR code atau masukkan kode manual</p>
    </div>

    <!-- Scanner Container -->
    <div class="bg-white rounded-lg shadow p-4">
        <div id="qr-reader" class="w-full rounded-lg overflow-hidden mb-4"></div>
        
        <div class="flex space-x-2">
            <button id="start-scan" onclick="startScanner()" class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                📷 Mulai Scan
            </button>
            <button id="stop-scan" onclick="stopScanner()" class="flex-1 bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 font-semibold hidden">
                ⏹️ Stop Scan
            </button>
        </div>
    </div>

    <!-- Manual Input -->
    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="font-semibold text-gray-900 mb-3">Input Manual</h3>
        <form onsubmit="validateCode(event)" class="space-y-3">
            <input 
                type="text" 
                id="manual-code" 
                placeholder="Masukkan kode voucher"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 font-mono uppercase"
            >
            <button type="submit" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 font-semibold">
                ✓ Validasi Kode
            </button>
        </form>
    </div>

    <!-- Result Card -->
    <div id="result-card" class="hidden">
        <!-- Valid Result -->
        <div id="valid-result" class="bg-white rounded-lg shadow p-6 hidden">
            <div class="text-center mb-4">
                <div class="text-6xl mb-2">✅</div>
                <h3 class="text-xl font-bold text-green-600">Voucher Valid!</h3>
            </div>

            <div class="space-y-3 mb-6">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500">Kode Voucher</p>
                    <p id="voucher-code" class="font-mono font-bold text-gray-900"></p>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500">Nama Customer</p>
                    <p id="user-name" class="font-semibold text-gray-900"></p>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500">Email</p>
                    <p id="user-email" class="text-gray-900"></p>
                </div>

                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500">PIC</p>
                    <p id="pic-name" class="text-gray-900"></p>
                </div>

                <div class="bg-purple-50 p-3 rounded-lg border border-purple-200">
                    <p class="text-xs text-purple-600 font-semibold">PENAWARAN</p>
                    <p id="offer-title" class="font-bold text-purple-900"></p>
                    <p id="offer-discount" class="text-sm text-purple-700"></p>
                </div>
            </div>

            <button onclick="redeemVoucher()" class="w-full bg-green-600 text-white px-4 py-4 rounded-lg hover:bg-green-700 font-bold text-lg">
                🎁 REDEEM VOUCHER
            </button>
        </div>

        <!-- Invalid Result -->
        <div id="invalid-result" class="bg-white rounded-lg shadow p-6 hidden">
            <div class="text-center mb-4">
                <div class="text-6xl mb-2">❌</div>
                <h3 class="text-xl font-bold text-red-600">Voucher Tidak Valid</h3>
            </div>

            <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-4">
                <p id="error-message" class="text-red-700"></p>
            </div>

            <button onclick="resetScan()" class="w-full bg-gray-600 text-white px-4 py-3 rounded-lg hover:bg-gray-700 font-semibold">
                Scan Lagi
            </button>
        </div>

        <!-- Success Result -->
        <div id="success-result" class="bg-white rounded-lg shadow p-6 hidden">
            <div class="text-center mb-4">
                <div class="text-6xl mb-2">🎉</div>
                <h3 class="text-xl font-bold text-green-600">Berhasil Diredeem!</h3>
            </div>

            <div class="bg-green-50 border border-green-200 p-4 rounded-lg mb-4">
                <p class="text-green-700 text-center">Voucher telah berhasil diredeem</p>
            </div>

            <button onclick="resetScan()" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                Scan Voucher Lain
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let html5QrCode;
let currentCode = '';

function startScanner() {
    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
    
    html5QrCode = new Html5Qrcode("qr-reader");
    
    html5QrCode.start(
        { facingMode: "environment" },
        config,
        (decodedText) => {
            stopScanner();
            document.getElementById('manual-code').value = decodedText;
            validateCodeDirect(decodedText);
        },
        (errorMessage) => {
            // Ignore scan errors
        }
    ).then(() => {
        document.getElementById('start-scan').classList.add('hidden');
        document.getElementById('stop-scan').classList.remove('hidden');
    }).catch((err) => {
        alert('Error starting camera: ' + err);
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            document.getElementById('start-scan').classList.remove('hidden');
            document.getElementById('stop-scan').classList.add('hidden');
        });
    }
}

function validateCode(event) {
    event.preventDefault();
    const code = document.getElementById('manual-code').value.trim().toUpperCase();
    if (code) {
        validateCodeDirect(code);
    }
}

function validateCodeDirect(code) {
    currentCode = code;
    
    fetch('{{ route("merchant.scan.validate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            showValidResult(data.voucher);
        } else {
            showInvalidResult(data.error);
        }
    })
    .catch(error => {
        showInvalidResult('Terjadi kesalahan. Silakan coba lagi.');
    });
}

function showValidResult(voucher) {
    document.getElementById('result-card').classList.remove('hidden');
    document.getElementById('valid-result').classList.remove('hidden');
    document.getElementById('invalid-result').classList.add('hidden');
    document.getElementById('success-result').classList.add('hidden');
    
    document.getElementById('voucher-code').textContent = voucher.code;
    document.getElementById('user-name').textContent = voucher.user_name;
    document.getElementById('user-email').textContent = voucher.user_email;
    document.getElementById('pic-name').textContent = voucher.pic_name;
    document.getElementById('offer-title').textContent = voucher.offer_title;
    document.getElementById('offer-discount').textContent = voucher.offer_discount;
}

function showInvalidResult(error) {
    document.getElementById('result-card').classList.remove('hidden');
    document.getElementById('valid-result').classList.add('hidden');
    document.getElementById('invalid-result').classList.remove('hidden');
    document.getElementById('success-result').classList.add('hidden');
    
    document.getElementById('error-message').textContent = error;
}

function redeemVoucher() {
    if (!confirm('Yakin ingin redeem voucher ini?')) return;
    
    fetch('{{ route("merchant.scan.redeem") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ code: currentCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('valid-result').classList.add('hidden');
            document.getElementById('success-result').classList.remove('hidden');
        } else {
            showInvalidResult(data.error);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}

function resetScan() {
    document.getElementById('result-card').classList.add('hidden');
    document.getElementById('manual-code').value = '';
    currentCode = '';
}
</script>
@endsection
