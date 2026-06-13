@extends('layouts.app')

@section('header')
    <h2 class="h4 font-weight-bold text-dark mb-0">
        <i class="bi bi-display me-2"></i> Layar Dapur (KDS)
    </h2>
@endsection

@section('content')
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($orders->isEmpty())
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    <h3 class="mt-3 text-secondary">Semua pesanan sudah selesai!</h3>
                    <p class="text-muted">Tidak ada antrian masakan saat ini.</p>
                </div>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach($orders as $order)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0 fw-bold">
                                Meja {{ $order->table ? $order->table->table_number : 'Takeaway' }}
                            </h5>
                            <small class="opacity-75">
                                {{ $order->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach($order->items as $item)
                                <li class="list-group-item p-3 {{ $item->status == 1 ? 'bg-warning bg-opacity-10' : '' }}">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="badge bg-primary rounded-pill me-2">{{ $item->quantity }}x</span>
                                            <span class="fw-bold fs-5">{{ $item->menu->name }}</span>
                                        </div>
                                    </div>
                                    @if($item->note)
                                        <div class="text-danger small mb-2 border-start border-danger border-3 ps-2">
                                            <i class="bi bi-exclamation-circle me-1"></i>Catatan: {{ $item->note }}
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3">
                                        <form action="{{ route('kitchen.items.status', $item->id) }}" method="POST">
                                            @csrf
                                            @if($item->status == 0)
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">
                                                    <i class="bi bi-fire me-1"></i> Terima Pesanan
                                                </button>
                                            @elseif($item->status == 1)
                                                <input type="hidden" name="status" value="2">
                                                <button type="submit" class="btn btn-success w-100 fw-bold">
                                                    <i class="bi bi-check2-all me-1"></i> Selesai (Siap Saji)
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer bg-light text-muted text-center small">
                            ID: {{ $order->id }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Auto-refresh script -->
    <script>
        let refreshTimer = setTimeout(function() {
            window.location.reload();
        }, 15000); // 15 seconds

        // Reset timer if user interacts with forms to prevent refreshing while they are clicking
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                clearTimeout(refreshTimer);
            });
        });
    </script>
@endsection
