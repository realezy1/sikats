@extends('layouts.app')

@section('header')
    Detail Transaksi
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Invoice #{{ $order->id }}</h4>
        <div>
            <a href="{{ route('cashier.sales.index') }}" class="btn btn-light rounded-pill px-4 me-2 border">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('cashier.orders.receipt', $order->id) }}" target="_blank" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-printer me-1"></i> Cetak Struk
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informasi Transaksi -->
        <div class="col-lg-4">
            <div class="custom-card p-4 h-100">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Umum</h5>
                
                <div class="mb-3">
                    <span class="text-muted d-block small">ID Transaksi</span>
                    <span class="fw-bold text-dark fs-6">{{ $order->id }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="text-muted d-block small">Status</span>
                    <span class="badge bg-success">Selesai</span>
                </div>
                
                <div class="mb-3">
                    <span class="text-muted d-block small">Waktu Order</span>
                    <span class="fw-semibold text-dark">{{ $order->created_at->format('d M Y, H:i:s') }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="text-muted d-block small">Meja</span>
                    <span class="fw-semibold text-dark">Meja {{ $order->table ? $order->table->table_number : '-' }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="text-muted d-block small">Pelanggan</span>
                    <span class="fw-semibold text-dark">{{ $order->customer_name ?? '-' }}</span>
                </div>
                
                <div class="mb-3">
                    <span class="text-muted d-block small">Kasir / Pelayan</span>
                    <span class="fw-semibold text-dark">{{ $order->user ? $order->user->name : 'Self-Order' }}</span>
                </div>
            </div>
        </div>

        <!-- Detail Rincian Pesanan -->
        <div class="col-lg-8">
            <div class="custom-card p-4 h-100">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Rincian Pesanan & Pembayaran</h5>
                
                <div class="table-responsive mb-4">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item Menu</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->menu->name }}</div>
                                        @if($item->note)
                                            <small class="text-muted"><i class="bi bi-chat-left-text me-1"></i> {{ $item->note }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end text-muted">Rp {{ number_format($item->price_at_order, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold text-dark">Total Tagihan</td>
                                <td class="text-end fw-bolder fs-5 text-primary border-top border-2">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="bg-light p-4 rounded-3">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Info Pembayaran</h6>
                    <div class="row">
                        <div class="col-sm-6 mb-2 mb-sm-0">
                            <span class="text-muted d-block small">Metode Pembayaran</span>
                            <span class="fw-bold text-uppercase {{ $order->payment_method === 'cash' ? 'text-success' : 'text-primary' }}">
                                {{ $order->payment_method }}
                            </span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted d-block small">Waktu Pembayaran</span>
                            <span class="fw-semibold text-dark">{{ $order->payment_time ? $order->payment_time->format('d M Y, H:i:s') : '-' }}</span>
                        </div>
                    </div>
                    
                    @if($order->payment_method === 'cash')
                        <div class="row mt-3 pt-3 border-top border-light">
                            <div class="col-sm-6 mb-2 mb-sm-0">
                                <span class="text-muted d-block small">Uang Diterima</span>
                                <span class="fw-semibold text-dark">Rp {{ number_format($order->cash_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block small">Kembalian</span>
                                <span class="fw-semibold text-success">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @elseif($order->payment_method === 'midtrans')
                        <div class="row mt-3 pt-3 border-top border-light">
                            <div class="col-12">
                                <span class="text-muted d-block small">Midtrans Order ID</span>
                                <span class="fw-semibold text-secondary font-monospace">{{ $order->midtrans_order_id ?? '-' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
