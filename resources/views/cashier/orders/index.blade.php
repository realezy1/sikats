@extends('layouts.app')

@section('header')
    Daftar Pesanan Aktif
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Pesanan Aktif</h4>
        <a href="{{ route('cashier.orders.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-1"></i> Buat Pesanan Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div id="orders-wrapper">
        @include('cashier.orders.partials.order_list')
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Poll for active orders every 5 seconds
        setInterval(function() {
            fetch('{{ route('cashier.orders.active.data') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                const wrapper = document.getElementById('orders-wrapper');
                if (wrapper) {
                    wrapper.innerHTML = html;
                }
            })
            .catch(error => console.error('Error fetching active orders:', error));
        }, 5000);
    });
</script>
@endpush
