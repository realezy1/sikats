<div class="row g-4" id="active-orders-container">
    @forelse($orders as $order)
        <div class="col-md-4 col-lg-3">
            <div class="custom-card p-4 h-100 d-flex flex-column position-relative border-top border-4 border-{{ $order->status === 'unpaid' ? 'danger' : ($order->status === 'proses' ? 'warning' : 'success') }}">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold">Meja {{ $order->table->table_number }}</span>
                            @if($order->status === 'unpaid')
                                <span class="badge bg-danger-subtle text-danger fw-semibold">Belum Bayar</span>
                            @elseif($order->status === 'proses')
                                <span class="badge bg-warning-subtle text-warning fw-semibold">Dapur</span>
                            @elseif($order->status === 'ready')
                                <span class="badge bg-success-subtle text-success fw-semibold">Siap Saji</span>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-1">{{ $order->id }}</h6>
                        <p class="text-muted small mb-0">{{ $order->customer_name ?? 'Pelanggan Dine-in' }}</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center text-muted small mb-4">
                    <i class="bi bi-clock me-2"></i>
                    {{ $order->created_at->diffForHumans() }}
                </div>

                <div class="d-flex flex-column gap-2 mt-auto">
                    <a href="{{ route('cashier.orders.show', $order->id) }}" class="btn btn-light w-100 rounded-pill fw-medium text-primary hover-bg-primary hover-text-white transition-all">
                        Kelola Pesanan
                    </a>
                    @if($order->status === 'ready')
                        <form action="{{ route('cashier.orders.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold text-white">
                                <i class="bi bi-check2-all me-1"></i> Sajikan & Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="text-muted mb-3">
                <i class="bi bi-receipt display-4"></i>
            </div>
            <h5 class="fw-bold text-dark">Belum Ada Pesanan Aktif</h5>
            <p class="text-secondary">Silakan buat pesanan baru untuk mulai melayani pelanggan.</p>
        </div>
    @endforelse
</div>
