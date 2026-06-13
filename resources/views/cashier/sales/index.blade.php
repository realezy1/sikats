@extends('layouts.app')

@section('header')
    Riwayat Penjualan
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Riwayat Penjualan</h4>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="custom-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="border-0 rounded-start-pill py-3 px-4">ID Transaksi</th>
                        <th class="border-0 py-3">Waktu</th>
                        <th class="border-0 py-3">Meja</th>
                        <th class="border-0 py-3">Pelanggan</th>
                        <th class="border-0 py-3">Kasir/Pelayan</th>
                        <th class="border-0 py-3">Metode</th>
                        <th class="border-0 py-3 text-end">Total (Rp)</th>
                        <th class="border-0 rounded-end-pill py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><span class="fw-bold text-primary">{{ $order->id }}</span></td>
                            <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td>Meja {{ $order->table ? $order->table->table_number : '-' }}</td>
                            <td>{{ $order->customer_name ?? '-' }}</td>
                            <td>{{ $order->user ? $order->user->name : 'Self-Order' }}</td>
                            <td>
                                @if($order->payment_method === 'cash')
                                    <span class="badge bg-success-subtle text-success">Tunai</span>
                                @elseif($order->payment_method === 'midtrans')
                                    <span class="badge bg-primary-subtle text-primary">Midtrans</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <a href="{{ route('cashier.sales.show', $order->id) }}" class="btn btn-sm btn-light text-primary border rounded-pill px-3">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada riwayat transaksi penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
