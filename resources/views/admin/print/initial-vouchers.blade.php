<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Voucher Print</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .voucher {
            width: 190mm;
            height: 60mm;
            border: 2px solid #0d7377;
            border-radius: 8px;
            margin-bottom: 5mm;
            position: relative;
            page-break-inside: avoid;
            display: flex;
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        /* Force page break after every 4 vouchers */
        .voucher:nth-child(4n) {
            page-break-after: always;
            margin-bottom: 0;
        }
        
        /* Left section - Text info */
        .text-section {
            flex: 1;
            padding: 12mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        /* Dashed separator line */
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
        
        .campaign-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .campaign-logo {
            font-size: 28px;
            margin-right: 8px;
            color: #d4af37;
        }
        
        .campaign-title {
            font-size: 18px;
            font-weight: bold;
            color: #0d7377;
            text-transform: uppercase;
        }
        
        .campaign-subtitle {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }
        
        .scan-instruction {
            font-size: 15px;
            font-weight: 600;
            color: #0d7377;
            margin-bottom: 8px;
        }
        
        .pic-info {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 8px;
        }
        
        .decorative-line {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #0d7377, #d4af37);
            margin: 8px 0;
            border-radius: 2px;
        }
        
        /* Right section - QR Code */
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
        
        .qr-code img {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    @foreach($vouchers as $voucher)
        <div class="voucher">
            <!-- Left: Text Section -->
            <div class="text-section">
                <div class="campaign-header">
                    <div class="campaign-logo">☪</div>
                    <div>
                        <div class="campaign-title">Voucher Ramadhan</div>
                        <div class="campaign-subtitle">1446 H</div>
                    </div>
                </div>
                
                <div class="decorative-line"></div>
                
                <div class="scan-instruction">Scan QR Code untuk Klaim Voucher</div>
                
                <div class="pic-info">
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
                <div class="qr-code">
                    <?php
                        $claimUrl = config('app.url') . '/claim/' . $voucher->code;
                        $qrUrl = 'https://chart.googleapis.com/chart?chs=165x165&cht=qr&chl=' . urlencode($claimUrl) . '&choe=UTF-8';
                    ?>
                    <img src="{{ $qrUrl }}" alt="QR Code" style="width: 100%; height: 100%;">
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>
