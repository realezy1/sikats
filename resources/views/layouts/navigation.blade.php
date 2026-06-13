<!-- Logo Area -->
<div class="d-flex align-items-center mb-4 px-2">
    <div class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
        <i class="bi bi-box-fill fs-5"></i>
    </div>
    <span class="fs-4 fw-bold text-dark">SiKats</span>
</div>

<!-- Navigation Links -->
<div class="flex-grow-1">
    
    <div class="text-muted small fw-bold text-uppercase tracking-wider px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">Menu Utama</div>
    
    <ul class="nav flex-column gap-1 mb-4">
        @if(Auth::user()->role_id == 1)
        <!-- Users (Admin only) -->
        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 {{ request()->routeIs('admin.users.*') ? 'bg-light text-dark fw-bold' : 'text-secondary hover-bg-light' }}">
                <i class="bi bi-people me-3 {{ request()->routeIs('admin.users.*') ? 'text-primary' : 'text-muted' }}"></i>
                Manajemen User
            </a>
        </li>

        <!-- Meja & QR Code (Admin only) -->
        <li class="nav-item">
            <a href="{{ route('admin.tables.index') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 {{ request()->routeIs('admin.tables.*') ? 'bg-light text-dark fw-bold' : 'text-secondary hover-bg-light' }}">
                <i class="bi bi-grid-3x3-gap me-3 {{ request()->routeIs('admin.tables.*') ? 'text-primary' : 'text-muted' }}"></i>
                Meja & QR Code
            </a>
        </li>

        <!-- Produk (Katalog Menu & Kategori) -->
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center px-3 py-2 rounded-3 text-secondary text-decoration-none" data-bs-toggle="collapse" href="#productsCollapse" role="button" aria-expanded="{{ request()->routeIs('admin.menus.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }}">
                <div class="d-flex align-items-center">
                    <i class="bi bi-box-seam me-3 text-muted"></i>
                    Produk
                </div>
                <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.menus.*') || request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="productsCollapse">
                <ul class="nav flex-column ms-4 ps-2 border-start mt-1 gap-1">
                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}" class="nav-link small py-1 text-decoration-none {{ request()->routeIs('admin.categories.*') ? 'text-dark fw-semibold' : 'text-secondary' }}">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.menus.index') }}" class="nav-link small py-1 text-decoration-none {{ request()->routeIs('admin.menus.*') ? 'text-dark fw-semibold' : 'text-secondary' }}">Daftar Menu</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif

        <!-- Orders (Admin, Kasir) -->
        @if(in_array(Auth::user()->role_id, [1, 2]))
        <li class="nav-item">
            <a href="{{ route('cashier.orders.index') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none {{ request()->routeIs('cashier.orders.*') ? 'bg-light text-dark fw-bold' : 'text-secondary hover-bg-light' }}">
                <i class="bi bi-cart2 me-3 {{ request()->routeIs('orders.*') ? 'text-primary' : 'text-muted' }}"></i>
                Kasir (POS)
            </a>
        </li>
        @endif

        

        <!-- Sales (Admin, Kasir) -->
        @if(in_array(Auth::user()->role_id, [1, 2]))
        <li class="nav-item">
            <a href="{{ route('cashier.sales.index') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none {{ request()->routeIs('cashier.sales.*') ? 'bg-light text-dark fw-bold' : 'text-secondary hover-bg-light' }}">
                <i class="bi bi-currency-dollar me-3 {{ request()->routeIs('sales.*') ? 'text-primary' : 'text-muted' }}"></i>
                Riwayat Penjualan
            </a>
        </li>
        @endif

        <!-- Layar Dapur (Admin, Dapur) -->
        @if(in_array(Auth::user()->role_id, [1, 3]))
        <li class="nav-item">
            <a href="{{ route('kitchen.dashboard') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-secondary hover-bg-light">
                <i class="bi bi-display fs-5 me-3"></i> Layar Dapur
            </a>
        </li>
        @endif

        <!-- Reports (Admin only) -->
        @if(Auth::user()->role_id == 1)
        <li class="nav-item">
            <a href="{{ route('admin.reports.index') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded-3 text-decoration-none {{ request()->routeIs('admin.reports.*') ? 'bg-light text-dark fw-bold' : 'text-secondary hover-bg-light' }}">
                <i class="bi bi-file-earmark-text me-3 {{ request()->routeIs('admin.reports.*') ? 'text-primary' : 'text-muted' }}"></i>
                Laporan
            </a>
        </li>
        @endif

    </ul>
</div>

<style>
    .hover-bg-light:hover {
        background-color: #f8f9fa;
        color: #212529 !important;
    }
</style>
