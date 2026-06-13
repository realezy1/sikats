<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pesanan - {{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; margin: 0; padding: 20px; }
        .text-center { text-align: center; }
        .mb-2 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 20px; }
        .w-100 { width: 100%; }
        .border-bottom { border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;}
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 4px 0; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .title { font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="text-center mb-4 border-bottom">
        <div class="title">SiKats</div>
    </div>

    <div class="mb-4 border-bottom">
        <table class="w-100">
            <tr>
                <td><strong>No. Pesanan:</strong></td>
                <td class="text-right">{{ $order->id }}</td>
            </tr>
            <tr>
                <td><strong>Meja:</strong></td>
                <td class="text-right">{{ $order->table->table_number }}</td>
            </tr>
            <tr>
                <td><strong>Pelanggan:</strong></td>
                <td class="text-right">{{ $order->customer_name ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Kasir:</strong></td>
                <td class="text-right">{{ optional($order->user)->name ?? 'Sistem' }}</td>
            </tr>
            <tr>
                <td><strong>Waktu Bayar:</strong></td>
                <td class="text-right">{{ $order->payment_time ? $order->payment_time->format('d M Y H:i') : '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="border-bottom mb-2">
        <table class="w-100">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->menu->name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-4 border-bottom">
        <table class="w-100">
            <tr>
                <td class="font-bold">Total</td>
                <td class="text-right font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pembayaran</td>
                <td class="text-right">{{ strtoupper($order->payment_method) }}</td>
            </tr>
            @if($order->payment_method === 'cash')
            <tr>
                <td>Tunai</td>
                <td class="text-right">Rp {{ number_format($order->cash_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembali</td>
                <td class="text-right">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="text-center mt-4">
        <div>Terima Kasih atas kunjungan Anda!</div>
        <div style="font-size: 10px; margin-top: 10px;">Powered by SiKats</div>
    </div>
</body>
</html>
