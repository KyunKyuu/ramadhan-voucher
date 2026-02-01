<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Vouchers - Ramadhan Berkah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- QR Code Library - Client Side -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }
            
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            .voucher {
                page-break-inside: avoid;
            }
            
            /* Force page break after every 4 vouchers */
            .voucher:nth-child(4n) {
                page-break-after: always;
            }
        }
        
        .voucher {
            width: 100%;
            max-width: 190mm;
            height: 60mm;
            border: 2px solid #0d7377;
            border-radius: 8px;
            margin-bottom: 5mm;
            display: flex;
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        .text-section {
            flex: 1;
            padding: 12mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        .text-section::after {
            content: '';
            position: absolute;
            right: 0;
            top: 10mm;
            bottom: 10mm;
            width: 2px;
            background-image: repeating-linear-gradient(
                to bottom,
                #0d7377,
                #0d7377 4px,
                transparent 4px,
                transparent 8px
            );
        }
        
        .qr-section {
            width: 60mm;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            padding: 8mm;
        }
        
        .qr-code {
            width: 44mm;
            height: 44mm;
        }

        .qr-code canvas {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body class="bg-gray-100 p-4">
    <!-- Print Button (Hidden when printing) -->
    <div class="no-print max-w-4xl mx-auto mb-4 bg-white rounded-lg shadow p-4 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Print Preview - {{ $vouchers->count() }} Voucher</h1>
            <p class="text-sm text-gray-600">QR Code akan muncul dalam beberapa detik...</p>
        </div>
        <div class="space-x-2">
            <button onclick="window.print()" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 font-medium">
                🖨️ Print
            </button>
            <a href="{{ route('admin.vouchers.print') }}" class="inline-block bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Vouchers Container -->
    <div class="max-w-4xl mx-auto space-y-2">
        @foreach($vouchers as $index => $voucher)
            <div class="voucher">
                <!-- Left: Text Section -->
                <div class="text-section">
                    <div class="flex items-center mb-2">
                        <div class="text-3xl mr-2 text-yellow-600">☪</div>
                        <div>
                            <div class="text-lg font-bold text-teal-700">Voucher Ramadhan</div>
                            <div class="text-xs text-gray-600">1446 H</div>
                        </div>
                    </div>
                    
                    <div class="w-12 h-1 bg-gradient-to-r from-teal-700 to-yellow-600 rounded my-2"></div>
                    
                    <div class="text-base font-semibold text-teal-700 mb-2">Scan QR Code untuk Klaim Voucher</div>
                    
                    <div class="text-xs text-gray-500">
                        @if($voucher->pic)
                            <strong>PIC:</strong> {{ $voucher->pic->name }}
                        @endif
                        @if($voucher->batch)
                            @if($voucher->pic) | @endif
                            <strong>Batch:</strong> {{ $voucher->batch->name }}
                        @endif
                    </div>
                </div>
                
                <!-- Right: QR Code Section -->
                <div class="qr-section">
                    <div class="qr-code" id="qr-{{ $index }}" data-url="{{ config('app.url') }}/claim/{{ $voucher->code }}"></div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        console.log('=== QR Code Generation Debug ===');
        console.log('Page loaded, starting QR generation...');
        
        // Check if QRCode library is loaded
        if (typeof QRCode === 'undefined') {
            console.error('ERROR: QRCode library not loaded!');
            alert('QRCode library gagal dimuat. Periksa koneksi internet.');
        } else {
            console.log('✓ QRCode library loaded successfully');
        }
        
        // Generate QR codes using JavaScript (client-side)
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, finding QR elements...');
            
            const qrElements = document.querySelectorAll('[id^="qr-"]');
            console.log('Found ' + qrElements.length + ' QR elements');
            
            if (qrElements.length === 0) {
                console.error('ERROR: No QR elements found!');
                return;
            }
            
            qrElements.forEach(function(element, index) {
                const url = element.getAttribute('data-url');
                console.log('Generating QR #' + index + ' for URL: ' + url);
                
                try {
                    // Generate QR code
                    const qrcode = new QRCode(element, {
                        text: url,
                        width: 165,
                        height: 165,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: QRCode.CorrectLevel.H
                    });
                    
                    console.log('✓ QR #' + index + ' generated successfully');
                    
                    // Check if canvas was created
                    const canvas = element.querySelector('canvas');
                    const img = element.querySelector('img');
                    
                    if (canvas) {
                        console.log('✓ Canvas created for QR #' + index);
                    } else if (img) {
                        console.log('✓ Image created for QR #' + index);
                    } else {
                        console.error('✗ No canvas or image found for QR #' + index);
                    }
                    
                } catch (error) {
                    console.error('ERROR generating QR #' + index + ':', error);
                    element.innerHTML = '<div style="color: red; font-size: 12px;">Error: ' + error.message + '</div>';
                }
            });
            
            console.log('=== QR Generation Complete ===');
            console.log('Check browser console for any errors');
            
            // Show success message
            setTimeout(function() {
                const successMsg = document.createElement('div');
                successMsg.className = 'no-print fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                successMsg.innerHTML = '✓ ' + qrElements.length + ' QR codes generated!';
                document.body.appendChild(successMsg);
                
                setTimeout(function() {
                    successMsg.remove();
                }, 3000);
            }, 1000);
        });
    </script>
</body>
</html>
