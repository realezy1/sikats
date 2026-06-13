@extends('layouts.customer')

@section('content')

<!-- Mobile Cart Backdrop -->
<div class="cart-backdrop" id="cartBackdrop"></div>

<div class="container-fluid p-0">
    <div class="d-flex">
        
        <!-- Main Content (Menu) -->
        <div class="main-content flex-grow-1 p-3 p-md-4">
            
            <!-- Header Area -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1">Meja {{ $table->table_number }}</h4>
                    <p class="text-muted small mb-0">Silakan pilih pesanan Anda</p>
                </div>
            </div>

            <!-- Categories Scroll -->
            <div class="d-flex overflow-auto gap-2 mb-4 pb-2" style="scrollbar-width: none;">
                <a href="#" class="category-tab active" data-category="all">Semua</a>
                @foreach($categories as $category)
                    <a href="#" class="category-tab" data-category="{{ $category->id }}">{{ $category->name }}</a>
                @endforeach
            </div>

            <!-- Menu Grid -->
            <div class="row g-3" id="menuGrid">
                @foreach($menus as $menu)
                <div class="col-6 col-md-4 col-lg-3 menu-item-card" data-category="{{ $menu->category_id }}">
                    <div class="card menu-card" style="cursor: pointer;" onclick="showMenuDetail({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->photo ? Storage::url($menu->photo) : '' }}', '{{ addslashes($menu->description ?? '') }}')">
                        <div class="menu-img-wrapper">
                            @if($menu->photo)
                                <img src="{{ Storage::url($menu->photo) }}" class="menu-img" alt="{{ $menu->name }}">
                            @else
                                <i class="bi bi-image text-muted fs-1"></i>
                            @endif
                        </div>
                        <div class="card-body p-3 d-flex flex-column">
                            <h6 class="fw-bold text-dark mb-1 fs-6 text-truncate" title="{{ $menu->name }}">{{ $menu->name }}</h6>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                <span class="fw-bold text-dark small">Rp{{ number_format($menu->price, 0, ',', '.') }}</span>
                                <button class="btn btn-add" 
                                    onclick="event.stopPropagation(); addToCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->photo ? Storage::url($menu->photo) : '' }}')">
                                    <i class="bi bi-plus-lg fs-6"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        <!-- Right Sidebar (Cart) -->
        <div class="cart-sidebar" id="cartSidebar">
            
            <!-- Mobile Handle -->
            <div class="d-block d-lg-none text-center pt-2 pb-1" id="mobileCartHandle">
                <div class="mx-auto bg-secondary rounded-pill" style="width: 40px; height: 4px; opacity: 0.3;"></div>
            </div>

            <!-- Cart Header -->
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Keranjang</h5>
                <button class="btn btn-sm btn-link text-danger text-decoration-none px-0" onclick="clearCart()">
                    Kosongkan
                </button>
            </div>

            <!-- Cart Items Scrollable -->
            <div class="flex-grow-1 overflow-auto p-3" id="cartItemsContainer">
                <!-- Items will be injected here by JS -->
                <div class="text-center text-muted mt-5 pt-4" id="emptyCartMessage">
                    <i class="bi bi-cart3 fs-1 mb-2"></i>
                    <p>Keranjang masih kosong</p>
                </div>
            </div>

            <!-- Cart Footer -->
            <div class="p-4 border-top bg-white">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total</span>
                    <span class="fw-bold fs-5 text-dark" id="cartTotal">Rp0</span>
                </div>
                <button class="btn btn-danger w-100 py-3 rounded-3 fw-bold fs-6 shadow-sm" id="btnCheckout" onclick="openCustomerNameModal()" disabled>
                    Pesan & Bayar
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Mobile Floating Cart Button -->
<button class="btn btn-danger floating-cart-btn" id="floatingCartBtn" onclick="toggleMobileCart()">
    <i class="bi bi-cart3 fs-5"></i>
    <span class="fw-bold" id="floatingCartTotal">Rp0</span>
    <span class="badge bg-white text-danger ms-2 rounded-pill" id="floatingCartCount">0</span>
</button>

<!-- Menu Detail Modal -->
<div class="modal fade" id="menuDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-0 pb-0 position-absolute w-100 z-3" style="top: 0; right: 0;">
        <button type="button" class="btn-close bg-white rounded-circle p-2 shadow-sm m-3 ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="position-relative bg-light rounded-top-4 overflow-hidden d-flex align-items-center justify-content-center" style="height: 250px;" id="modalImageContainer">
          <img src="" id="modalMenuImage" class="w-100 h-100 object-fit-cover" alt="Menu Image" style="display: none;">
          <i class="bi bi-image text-muted" id="modalMenuIcon" style="font-size: 5rem; display: none;"></i>
      </div>
      <div class="modal-body p-4">
        <h4 class="fw-bold text-dark mb-1" id="modalMenuName">Menu Name</h4>
        <h5 class="fw-bold text-danger mb-3" id="modalMenuPrice">Rp0</h5>
        <p class="text-muted small mb-4" id="modalMenuDesc" style="line-height: 1.6;">Deskripsi tidak tersedia.</p>
        
        <button type="button" class="btn btn-danger w-100 py-3 rounded-3 fw-bold shadow-sm" id="modalBtnAdd" onclick="modalAddToCart()">
            <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Customer Name Modal -->
<div class="modal fade" id="customerNameModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-bold text-dark">Informasi Pemesan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 pt-3">
        <p class="text-muted small mb-3">Atas nama siapa pesanan ini dibuat?</p>
        <div class="mb-4">
            <input type="text" class="form-control form-control-lg bg-light" id="customerNameInput" placeholder="Masukkan nama Anda (contoh: Budi)" required>
            <div class="invalid-feedback">Nama pemesan wajib diisi.</div>
        </div>
        <button type="button" class="btn btn-danger w-100 py-3 rounded-3 fw-bold shadow-sm" id="btnSubmitCheckout" onclick="submitCustomerName()">
            Lanjutkan Pembayaran
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
    // Constants
    const tableId = {{ $table->id }};
    const qrToken = '{{ $table->qr_token }}';
    const checkoutUrl = '{{ route("customer.checkout") }}';
    const successUrlBase = '{{ url("/order") }}'; // Will append /{order}/success
    const csrfToken = '{{ csrf_token() }}';

    // State
    let cart = [];

    // DOM Elements
    const menuItems = document.querySelectorAll('.menu-item-card');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const emptyCartMessage = document.getElementById('emptyCartMessage');
    const cartTotalEl = document.getElementById('cartTotal');
    const btnCheckout = document.getElementById('btnCheckout');
    const floatingCartTotal = document.getElementById('floatingCartTotal');
    const floatingCartCount = document.getElementById('floatingCartCount');
    
    // Mobile Cart Elements
    const cartSidebar = document.getElementById('cartSidebar');
    const cartBackdrop = document.getElementById('cartBackdrop');
    const mobileCartHandle = document.getElementById('mobileCartHandle');

    // Modal Elements
    const menuDetailModal = new bootstrap.Modal(document.getElementById('menuDetailModal'));
    const customerNameModal = new bootstrap.Modal(document.getElementById('customerNameModal'));
    const modalMenuImage = document.getElementById('modalMenuImage');
    const modalMenuIcon = document.getElementById('modalMenuIcon');
    const modalMenuName = document.getElementById('modalMenuName');
    const modalMenuPrice = document.getElementById('modalMenuPrice');
    const modalMenuDesc = document.getElementById('modalMenuDesc');
    const customerNameInput = document.getElementById('customerNameInput');
    const btnSubmitCheckout = document.getElementById('btnSubmitCheckout');
    let currentModalItem = null;

    // Category Filtering
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Update active state
            categoryTabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            const categoryId = tab.dataset.category;
            
            // Filter items
            menuItems.forEach(item => {
                if (categoryId === 'all' || item.dataset.category === categoryId) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Mobile Cart Toggle
    function toggleMobileCart() {
        cartSidebar.classList.toggle('show');
        cartBackdrop.classList.toggle('show');
    }

    cartBackdrop.addEventListener('click', toggleMobileCart);
    mobileCartHandle.addEventListener('click', toggleMobileCart);

    // Formatter
    const formatCurrency = (amount) => {
        return 'Rp' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };

    // Modal logic
    function showMenuDetail(id, name, price, photoUrl, description) {
        currentModalItem = { id, name, price, photoUrl };
        
        modalMenuName.innerText = name;
        modalMenuPrice.innerText = formatCurrency(price);
        modalMenuDesc.innerText = description || 'Tidak ada deskripsi.';

        if (photoUrl) {
            modalMenuImage.src = photoUrl;
            modalMenuImage.style.display = 'block';
            modalMenuIcon.style.display = 'none';
        } else {
            modalMenuImage.style.display = 'none';
            modalMenuIcon.style.display = 'block';
        }

        menuDetailModal.show();
    }

    function modalAddToCart() {
        if (currentModalItem) {
            addToCart(currentModalItem.id, currentModalItem.name, currentModalItem.price, currentModalItem.photoUrl);
            menuDetailModal.hide();
        }
    }

    // Cart Actions
    function addToCart(id, name, price, photoUrl) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({ id, name, price, qty: 1, photo: photoUrl });
        }
        renderCart();
    }

    function updateQty(id, delta) {
        const itemIndex = cart.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            cart[itemIndex].qty += delta;
            if (cart[itemIndex].qty <= 0) {
                cart.splice(itemIndex, 1);
            }
            renderCart();
        }
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function renderCart() {
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '';
            cartItemsContainer.appendChild(emptyCartMessage);
            emptyCartMessage.style.display = 'block';
            btnCheckout.disabled = true;
            cartTotalEl.innerText = 'Rp0';
            floatingCartTotal.innerText = 'Rp0';
            floatingCartCount.innerText = '0';
            return;
        }

        emptyCartMessage.style.display = 'none';
        cartItemsContainer.innerHTML = '';
        
        let total = 0;
        let count = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.qty;
            total += itemTotal;
            count += item.qty;

            const photoHtml = item.photo 
                ? `<img src="${item.photo}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">` 
                : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="bi bi-image text-muted"></i></div>`;

            const html = `
                <div class="d-flex align-items-center mb-3">
                    ${photoHtml}
                    <div class="ms-3 flex-grow-1">
                        <div class="fw-semibold small text-dark lh-sm mb-1">${item.name}</div>
                        <div class="text-danger small fw-bold">${formatCurrency(item.price)}</div>
                    </div>
                    <div class="d-flex align-items-center bg-light rounded-pill px-2 py-1">
                        <button class="btn btn-sm text-secondary p-0 px-1 border-0" onclick="updateQty(${item.id}, -1)">
                            <i class="bi bi-dash"></i>
                        </button>
                        <span class="mx-2 small fw-bold" style="width: 16px; text-align: center;">${item.qty}</span>
                        <button class="btn btn-sm text-secondary p-0 px-1 border-0" onclick="updateQty(${item.id}, 1)">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>
            `;
            cartItemsContainer.insertAdjacentHTML('beforeend', html);
        });

        cartTotalEl.innerText = formatCurrency(total);
        floatingCartTotal.innerText = formatCurrency(total);
        floatingCartCount.innerText = count;
        btnCheckout.disabled = false;
        btnCheckout.innerHTML = `Pesan & Bayar (${formatCurrency(total)})`;
    }

    // Checkout Flow
    function openCustomerNameModal() {
        if (cart.length === 0) return;
        customerNameInput.value = '';
        customerNameInput.classList.remove('is-invalid');
        customerNameModal.show();
        // Focus the input after modal opens
        setTimeout(() => {
            customerNameInput.focus();
        }, 500);
    }

    function submitCustomerName() {
        const name = customerNameInput.value.trim();
        if (!name) {
            customerNameInput.classList.add('is-invalid');
            return;
        }
        
        customerNameInput.classList.remove('is-invalid');
        processCheckout(name);
    }

    async function processCheckout(customerName) {
        if (cart.length === 0) return;

        btnSubmitCheckout.disabled = true;
        btnSubmitCheckout.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...`;
        
        // Also disable background checkout button
        btnCheckout.disabled = true;

        const payload = {
            table_id: tableId,
            token: qrToken,
            customer_name: customerName,
            items: cart.map(item => ({
                menu_id: item.id,
                quantity: item.qty
            }))
        };

        try {
            const response = await fetch(checkoutUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.success && data.snap_token) {
                // Open Midtrans Snap Popup
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result){
                        // Redirect to success page
                        window.location.href = `${successUrlBase}/${data.order_id}/success`;
                    },
                    onPending: function(result){
                        // You might want to redirect to success too, or tell them to finish payment
                        window.location.href = `${successUrlBase}/${data.order_id}/success`;
                    },
                    onError: function(result){
                        alert("Pembayaran gagal. Silakan coba lagi.");
                        resetCheckoutState();
                    },
                    onClose: function(){
                        resetCheckoutState();
                    }
                });
            } else {
                alert(data.message || 'Gagal memproses pesanan.');
                resetCheckoutState();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan pada server.');
            resetCheckoutState();
        }
    }

    function resetCheckoutState() {
        customerNameModal.hide();
        btnSubmitCheckout.disabled = false;
        btnSubmitCheckout.innerHTML = `Lanjutkan Pembayaran`;
        
        btnCheckout.disabled = false;
        btnCheckout.innerHTML = `Pesan & Bayar (${cartTotalEl.innerText})`;
    }
</script>
@endpush
