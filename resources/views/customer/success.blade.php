@extends('layouts.customer')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light px-3">
    <div class="card shadow-sm border-0 rounded-4 w-100" style="max-width: 450px;">
        <div class="card-body text-center p-5">
            <div class="mb-4">
                <div class="bg-success-subtle text-success d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                    <i class="bi bi-check-lg" style="font-size: 3rem;"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Pembayaran Berhasil!</h4>
            <p class="text-muted mb-4">Pesanan Anda <strong>{{ $order->id }}</strong> telah kami terima dan sedang disiapkan oleh Dapur.</p>
            
            <div class="bg-light rounded p-3 mb-4 text-start">
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">No. Meja</span>
                    <span class="fw-bold text-dark">{{ $order->table->table_number }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted">Total Pembayaran</span>
                    <span class="fw-bold text-danger">Rp{{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Metode</span>
                    <span class="fw-bold text-dark text-capitalize">{{ $order->payment_method }}</span>
                </div>
            </div>

            <p class="small text-muted mb-0">Terima kasih telah memesan. Silakan tunggu pesanan diantar ke meja Anda.</p>
        </div>
    </div>
</div>
@endsection
