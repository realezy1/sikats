<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - Meja {{ $table->table_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0; /* Gray background for screen */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .print-container {
            background-color: white;
            width: 210mm;
            height: 297mm;
            padding: 20mm;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Shadow for screen */
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .restaurant-name {
            font-size: 48px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .subtitle {
            font-size: 24px;
            color: #666;
            margin-bottom: 50px;
        }
        .qr-wrapper {
            margin: 0 auto 40px auto;
            padding: 30px;
            background: white;
            border: 4px solid #000;
            display: inline-block;
            border-radius: 20px;
        }
        .table-number-box {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .table-number-large {
            display: block;
            font-size: 100px;
            line-height: 1;
            color: #000;
        }
        .instructions {
            font-size: 20px;
            color: #444;
            margin-top: 30px;
            line-height: 1.5;
            border-top: 2px dashed #ccc;
            padding-top: 30px;
        }
        .instruction-step {
            margin-bottom: 15px;
        }
        
        /* Print Styles */
        @media print {
            body {
                background-color: white;
            }
            .print-container {
                box-shadow: none;
                width: 100%;
                height: 100%;
            }
            .no-print {
                display: none;
            }
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #0d6efd;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-weight: bold;
        }
        .print-btn:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>

    <button class="no-print print-btn" onclick="window.print()">Cetak QR Code</button>

    <div class="print-container">
        
        <div class="restaurant-name">SiKats</div>
        <div class="subtitle">Self-Ordering System</div>
        
        <div class="qr-wrapper">
            <!-- SimpleSoftwareIO QR Code generates an SVG string -->
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(350)->margin(1)->generate($qrUrl) !!}
        </div>
        
        <div class="table-number-box">
            MEJA NOMOR
            <span class="table-number-large">{{ $table->table_number }}</span>
        </div>
        
        <div class="instructions">
            <div class="instruction-step"><strong>1.</strong> Buka aplikasi Kamera di HP Anda</div>
            <div class="instruction-step"><strong>2.</strong> Arahkan kamera ke QR Code di atas</div>
            <div class="instruction-step"><strong>3.</strong> Klik link yang muncul untuk mulai memesan</div>
        </div>
        
    </div>

</body>
</html>
