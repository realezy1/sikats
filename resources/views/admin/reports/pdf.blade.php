<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan SiKats</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        h1, h2, h3, h4, h5 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-weight-bold { font-weight: bold; }
        .text-muted { color: #6c757d; }
        
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .summary-box {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .summary-box td {
            width: 33.33%;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .summary-box .label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-box .value {
            display: block;
            font-size: 20px;
            font-weight: bold;
            color: #000;
        }

        .payment-methods {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        .payment-methods th, .payment-methods td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .payment-methods th {
            background-color: #f8f9fa;
        }

        .top-items {
            width: 100%;
            border-collapse: collapse;
        }
        .top-items th, .top-items td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .top-items th {
            background-color: #f8f9fa;
            text-align: left;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header text-center">
        <h1>Laporan Penjualan SiKats</h1>
        <p>Periode: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
    </div>

    <!-- Summary Metrics -->
    <table class="summary-box">
        <tr>
            <td>
                <span class="label">Total Omset</span>
                <span class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Total Transaksi</span>
                <span class="value">{{ number_format($totalTransactions, 0, ',', '.') }}</span>
            </td>
            <td>
                <span class="label">Rata-rata Transaksi</span>
                <span class="value">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <div style="width: 100%; display: block; clear: both; padding-top: 20px;">
        <!-- Payment Methods -->
        <h3 style="margin-bottom: 10px;">Pendapatan per Metode Pembayaran</h3>
        <table class="payment-methods">
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th class="text-right">Total Pendapatan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tunai (Cash)</td>
                    <td class="text-right font-weight-bold">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Digital (Midtrans)</td>
                    <td class="text-right font-weight-bold">Rp {{ number_format($midtransRevenue, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right font-weight-bold">Total</td>
                    <td class="text-right font-weight-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="width: 100%; display: block; clear: both; padding-top: 20px;">
        <!-- Top Selling Items -->
        <h3 style="margin-bottom: 10px;">Menu Terlaris (Top 10)</h3>
        <table class="top-items">
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 50%;">Nama Menu</th>
                    <th style="width: 20%; text-align: center;">Kuantitas Terjual</th>
                    <th style="width: 20%; text-align: right;">Total Pendapatan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topItems as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->menu->name }}</td>
                        <td class="text-center">{{ $item->total_qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data penjualan menu pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        Dicetak pada {{ \Carbon\Carbon::now()->format('d M Y, H:i') }} menggunakan Sistem SiKats.<br>
        Dokumen ini dibuat otomatis secara digital.
    </div>

</body>
</html>
