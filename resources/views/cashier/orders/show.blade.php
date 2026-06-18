@extends('layouts.app')

@section('header')
    Point of Sales
@endsection

@section('content')
<div class="row g-4">
    <!-- Katalog Menu (Kiri) -->
    <div class="col-lg-8">
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($order->status === 'unpaid')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0 text-dark">Katalog Menu</h5>
                <ul class="nav nav-pills gap-2" id="menuTab" role="tablist">
                    @foreach($categories as $idx => $category)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill {{ $idx === 0 ? 'active' : 'bg-white text-dark border' }}" id="cat-{{ $category->id }}-tab" data-bs-toggle="tab" data-bs-target="#cat-{{ $category->id }}" type="button" role="tab">
                                {{ $category->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="tab-content" id="menuTabContent">
                @foreach($categories as $idx => $category)
                    <div class="tab-pane fade {{ $idx === 0 ? 'show active' : '' }}" id="cat-{{ $category->id }}" role="tabpanel">
                        <div class="row g-3">
                            @foreach($category->menus as $menu)
                                <div class="col-md-4 col-sm-6">
                                    <div class="custom-card h-100 d-flex flex-column p-3 {{ $menu->stock <= 0 ? 'opacity-50' : '' }}">
                                        @if($menu->photo)
                                            <div class="rounded-3 mb-3 overflow-hidden bg-light" style="height: 140px;">
                                                <img src="{{ Storage::url($menu->photo) }}" class="w-100 h-100 object-fit-cover" alt="{{ $menu->name }}">
                                            </div>
                                        @else
                                            <div class="rounded-3 mb-3 bg-light d-flex align-items-center justify-content-center" style="height: 140px;">
                                                <i class="bi bi-image text-secondary fs-1"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1">{{ $menu->name }}</h6>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-primary fw-bold">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                                                @if($menu->stock > 0)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Stok: {{ $menu->stock }}</span>
                                                @else
                                                    <span class="badge bg-danger text-white rounded-pill">Habis</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($menu->stock > 0)
                                            <form action="{{ route('cashier.orders.items.store', $order->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="note" class="form-control" placeholder="Catatan (opsional)">
                                                    <button type="submit" class="btn btn-primary text-white px-3"><i class="bi bi-plus-lg"></i></button>
                                                </div>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary btn-sm w-100 mt-2" disabled>Habis</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($category->menus->isEmpty())
                                <div class="col-12 py-4 text-center text-muted">
                                    Tidak ada menu aktif di kategori ini.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Pelacakan Progress Dapur -->
            <div class="custom-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Progress Dapur</h5>
                        <p class="text-muted small mb-0">Status Order: 
                            @if($order->status === 'proses')
                                <span class="badge bg-warning-subtle text-warning fw-semibold px-3 py-1">Sedang Dimasak</span>
                            @elseif($order->status === 'ready')
                                <span class="badge bg-success-subtle text-success fw-semibold px-3 py-1">Siap Sajikan</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-secondary-subtle text-secondary fw-semibold px-3 py-1">Selesai / Disajikan</span>
                            @else
                                <span class="badge bg-info-subtle text-info fw-semibold px-3 py-1">{{ ucfirst($order->status) }}</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <i class="bi bi-clock-history fs-3 text-secondary"></i>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Jumlah</th>
                                <th>Status Item</th>
                                <th class="text-end">Waktu Dapur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->menu->name }}</div>
                                        @if($item->note)
                                            <small class="text-danger border-start border-danger border-2 ps-2 mt-1 d-block">
                                                <i class="bi bi-chat-left-text me-1"></i> {{ $item->note }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center fw-semibold">{{ $item->quantity }}</td>
                                    <td>
                                        @if($item->status == 0)
                                            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1">
                                                <i class="bi bi-hourglass-split me-1"></i> Antrean
                                            </span>
                                        @elseif($item->status == 1)
                                            <span class="badge bg-warning-subtle text-warning px-2.5 py-1">
                                                <span class="spinner-border spinner-border-sm me-1" style="width: 0.75rem; height: 0.75rem;" role="status"></span> Dimasak
                                            </span>
                                        @elseif($item->status == 2)
                                            <span class="badge bg-success-subtle text-success px-2.5 py-1">
                                                <i class="bi bi-check-circle me-1"></i> Siap
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end text-muted small">
                                        @if($item->ready_at)
                                            Selesai: {{ $item->ready_at->format('H:i') }}
                                        @elseif($item->accepted_at)
                                            Mulai: {{ $item->accepted_at->format('H:i') }}
                                        @else
                                            Menunggu
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Ringkasan Pesanan / Keranjang (Kanan Desktop, Bottom Offcanvas Mobile) -->
    <div class="col-lg-4">
        <div class="offcanvas-lg offcanvas-bottom custom-card sticky-top" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="top: 2rem; height: 85vh;">
            <div class="offcanvas-header d-lg-none border-bottom">
                <h5 class="offcanvas-title fw-bold" id="cartOffcanvasLabel">Keranjang (Meja {{ $order->table->table_number ?? '-' }})</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#cartOffcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body flex-column p-4">
                <div class="d-none d-lg-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">Keranjang</h5>
                    <span class="badge bg-primary rounded-pill px-3 py-2">Meja {{ $order->table->table_number }}</span>
                </div>
            
            <div class="mb-3 text-muted small">
                <div class="d-flex justify-content-between mb-1">
                    <span>Transaksi:</span>
                    <span class="fw-semibold text-dark">{{ $order->id }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Pelanggan:</span>
                    <span class="fw-semibold text-dark">{{ $order->customer_name ?? '-' }}</span>
                </div>
            </div>

            <!-- Daftar Item -->
            <div class="order-items-container mb-4" style="max-height: 40vh; overflow-y: auto;">
                @forelse($order->items as $item)
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 border-bottom border-light">
                        <div class="flex-grow-1 pe-3">
                            <h6 class="fw-bold mb-1 text-dark fs-6">{{ $item->menu->name }}</h6>
                            <div class="text-primary small fw-semibold mb-1">
                                {{ $item->quantity }} x Rp {{ number_format($item->price_at_order, 0, ',', '.') }}
                            </div>
                            @if($item->note)
                                <div class="text-muted small"><i class="bi bi-chat-left-text me-1"></i> {{ $item->note }}</div>
                            @endif
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-dark mb-2">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                            @if($order->status === 'unpaid' && $item->status == 0)
                                <a href="{{ route('cashier.orders.items.destroy', [$order->id, $item->id]) }}" class="btn btn-sm btn-outline-danger p-1 rounded" data-confirm-delete="true">
                                    <i class="bi bi-trash"></i>
                                </a>
                            @else
                                @if($item->status == 0)
                                    <span class="badge bg-secondary text-white" style="font-size: 0.65rem;">Antrean</span>
                                @elseif($item->status == 1)
                                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Dimasak</span>
                                @elseif($item->status == 2)
                                    <span class="badge bg-success text-white" style="font-size: 0.65rem;">Siap</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-cart-x fs-3 d-block mb-2"></i>
                        <span class="small">Keranjang masih kosong</span>
                    </div>
                @endforelse
            </div>

            <!-- Total & Action -->
            <div class="bg-light p-3 rounded-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-secondary">Total Tagihan</span>
                    <span class="fw-bolder fs-5 text-dark">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                @if($order->status !== 'unpaid')
                    <div class="border-top pt-2 mt-2 small text-muted">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Metode:</span>
                            <span class="fw-bold text-dark text-uppercase">{{ $order->payment_method }}</span>
                        </div>
                        @if($order->payment_method === 'cash')
                            <div class="d-flex justify-content-between mb-1">
                                <span>Bayar:</span>
                                <span class="fw-semibold text-dark">Rp {{ number_format($order->cash_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Kembalian:</span>
                                <span class="fw-semibold text-success">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <span>Waktu Pembayaran:</span>
                            <span class="fw-semibold text-dark">{{ $order->payment_time ? $order->payment_time->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                @endif
            </div>
            
            @if($order->status === 'unpaid')
                <button type="button" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm mb-2" data-bs-toggle="modal" data-bs-target="#checkoutModal" {{ $order->items->isEmpty() ? 'disabled' : '' }}>
                    <i class="bi bi-check2-circle me-1"></i> Checkout & Bayar
                </button>
            @else
                <a href="{{ route('cashier.orders.receipt', $order->id) }}" target="_blank" class="btn btn-primary w-100 rounded-pill fw-bold py-2 shadow-sm mb-2">
                    <i class="bi bi-printer me-1"></i> Cetak Struk
                </a>
                
                @if($order->status === 'ready')
                    <form action="{{ route('cashier.orders.complete', $order->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm">
                            <i class="bi bi-check2-all me-1"></i> Sajikan & Selesai
                        </button>
                    </form>
                @endif
            @endif
            
            @if($order->status === 'unpaid')
                <form id="cancelOrderForm" action="{{ route('cashier.orders.destroy', $order->id) }}" method="POST" class="mb-2">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="btnCancelOrder" class="btn btn-outline-danger w-100 rounded-pill fw-bold py-2 shadow-sm">
                        <i class="bi bi-x-circle me-1"></i> Batal Pesan (Hapus)
                    </button>
                </form>
            @endif
            
            <a href="{{ route('cashier.orders.index') }}" class="btn btn-light text-secondary w-100 rounded-pill fw-medium py-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button (Mobile Only) -->
<button class="btn btn-primary rounded-pill position-fixed bottom-0 start-50 translate-middle-x mb-4 shadow-lg d-lg-none d-flex align-items-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas" style="z-index: 1040; padding: 12px 24px;">
    <i class="bi bi-cart3 fs-5 me-2"></i> 
    <span class="fw-bold">Lihat Keranjang</span>
    @if($order->items->sum('quantity') > 0)
        <span class="badge bg-white text-primary ms-2 rounded-circle px-2 py-1">{{ $order->items->sum('quantity') }}</span>
    @endif
</button>

<!-- Modal Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="checkoutModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bg-light rounded p-3 mb-4 text-center">
                    <div class="text-muted small mb-1">Total Tagihan</div>
                    <div class="fs-3 fw-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                </div>

                <div class="d-grid gap-3" id="paymentOptions">
                    <button type="button" class="btn btn-outline-success py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center" id="btnChooseCash">
                        <i class="bi bi-cash-coin fs-4 me-3"></i> Bayar Tunai (Cash)
                    </button>
                    <button type="button" class="btn btn-outline-primary py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center btn-pay" data-method="midtrans">
                        <i class="bi bi-credit-card-2-front fs-4 me-3"></i> Bayar Online (Midtrans)
                    </button>
                </div>

                <div id="cashInputSection" class="d-none mt-4 border-top pt-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Uang Diterima (Rp)</label>
                        <input type="number" class="form-control form-control-lg" id="inputCashAmount" min="{{ $order->total }}" placeholder="Masukkan jumlah uang" required>
                    </div>
                    <div class="d-flex justify-content-between mb-3 bg-light p-2 rounded">
                        <span class="text-secondary fw-bold">Kembalian:</span>
                        <span class="text-success fw-bold fs-5" id="changeAmount">Rp 0</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary w-50" id="btnCancelCash">Batal</button>
                        <button type="button" class="btn btn-success w-50 fw-bold btn-pay" data-method="cash" id="btnProcessCash">Proses</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#menuTab button[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('show.bs.tab', event => {
                tabs.forEach(t => t.classList.add('bg-white', 'text-dark', 'border'));
                event.target.classList.remove('bg-white', 'text-dark', 'border');
            });
        });

        const btnChooseCash = document.getElementById('btnChooseCash');
        const paymentOptions = document.getElementById('paymentOptions');
        const cashInputSection = document.getElementById('cashInputSection');
        const btnCancelCash = document.getElementById('btnCancelCash');
        const inputCashAmount = document.getElementById('inputCashAmount');
        const changeAmountDisplay = document.getElementById('changeAmount');
        const orderTotal = {{ $order->total }};

        if (btnChooseCash) {
            btnChooseCash.addEventListener('click', function() {
                paymentOptions.classList.add('d-none');
                cashInputSection.classList.remove('d-none');
                setTimeout(() => inputCashAmount.focus(), 100);
            });
        }

        if (btnCancelCash) {
            btnCancelCash.addEventListener('click', function() {
                cashInputSection.classList.add('d-none');
                paymentOptions.classList.remove('d-none');
                inputCashAmount.value = '';
                changeAmountDisplay.innerText = 'Rp 0';
            });
        }

        if (inputCashAmount) {
            inputCashAmount.addEventListener('input', function() {
                const cash = parseFloat(this.value) || 0;
                let change = cash - orderTotal;
                if (change < 0) change = 0;
                changeAmountDisplay.innerText = 'Rp ' + change.toLocaleString('id-ID');
            });
        }

        const btnCancelOrder = document.getElementById('btnCancelOrder');
        if (btnCancelOrder) {
            btnCancelOrder.addEventListener('click', function() {
                Swal.fire({
                    title: 'Batal Pesanan!',
                    text: 'Apakah Anda yakin ingin membatalkan seluruh pesanan ini? Meja akan dikosongkan dan semua stok akan dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Batalkan!',
                    cancelButtonText: 'Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('cancelOrderForm').submit();
                    }
                });
            });
        }

        // Checkout Logic
        const payButtons = document.querySelectorAll('.btn-pay');
        payButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const method = this.getAttribute('data-method');
                let bodyData = { payment_method: method };
                
                if (method === 'cash') {
                    const cashVal = parseFloat(inputCashAmount.value) || 0;
                    if (cashVal < orderTotal) {
                        alert("Uang tunai kurang dari total tagihan!");
                        return;
                    }
                    bodyData.cash_amount = cashVal;
                }

                const originalHtml = this.innerHTML;
                payButtons.forEach(b => b.disabled = true);
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';

                fetch("{{ route('cashier.orders.checkout', $order->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(bodyData)
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        if(method === 'cash') {
                            // Redirect to receipt in new tab and reload current page
                            window.open(data.redirect_url, '_blank');
                            window.location.reload();
                        } else if(method === 'midtrans') {
                            // Close Modal
                            let myModalEl = document.getElementById('checkoutModal');
                            let modal = bootstrap.Modal.getInstance(myModalEl);
                            modal.hide();
                            
                            // Trigger Midtrans Snap
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result){
                                    fetch("{{ route('cashier.orders.midtrans.callback', $order->id) }}", {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify(result)
                                    }).then(() => {
                                        window.open("{{ route('cashier.orders.receipt', $order->id) }}", '_blank');
                                        window.location.reload();
                                    });
                                },
                                onPending: function(result){
                                    alert("Menunggu pembayaran Anda!");
                                    // reload to update status or buttons
                                    window.location.reload();
                                },
                                onError: function(result){
                                    alert("Pembayaran gagal!");
                                    window.location.reload();
                                },
                                onClose: function(){
                                    payButtons.forEach(b => b.disabled = false);
                                    btn.innerHTML = originalHtml;
                                }
                            });
                        }
                    } else {
                        alert('Terjadi kesalahan: ' + (data.message || 'Unknown error'));
                        payButtons.forEach(b => b.disabled = false);
                        btn.innerHTML = originalHtml;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan pada server.');
                    payButtons.forEach(b => b.disabled = false);
                    btn.innerHTML = originalHtml;
                });
            });
        });
    });
</script>
@endsection
