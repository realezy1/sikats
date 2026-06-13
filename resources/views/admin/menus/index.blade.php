@extends('layouts.app')

@section('header')
    Daftar Menu
@endsection

@section('content')
    


    <div class="row">
        <div class="col-12">
            <div class="custom-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center fw-bold text-dark fs-5">
                        <i class="bi bi-card-list me-2 text-muted"></i>
                        Daftar Menu
                    </div>
                    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Menu
                    </a>
                </div>

                <!-- Search Bar -->
                <form action="{{ route('admin.menus.index') }}" method="GET" class="mb-4">
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama menu atau kategori..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>

                @if(request('search'))
                    <div class="alert alert-light border py-2 px-3 small mb-3">
                        <i class="bi bi-info-circle me-1"></i> Menampilkan hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
                        — {{ $menus->total() }} data ditemukan.
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr class="text-muted small border-bottom">
                                <th class="pb-3 fw-medium">Foto</th>
                                <th class="pb-3 fw-medium">Menu </th>
                                <th class="pb-3 fw-medium">Kategori </th>
                                <th class="pb-3 fw-medium text-end">Harga </th>
                                <th class="pb-3 fw-medium text-center">Stok </th>
                                <th class="pb-3 fw-medium text-center">Status</th>
                                <th class="pb-3 fw-medium text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($menus as $menu)
                            <tr>
                                <td class="py-3">
                                    @if($menu->photo)
                                        <div class="rounded overflow-hidden shadow-sm" style="width: 48px; height: 48px;">
                                            <img src="{{ Storage::url($menu->photo) }}" alt="{{ $menu->name }}" class="w-100 h-100 object-fit-cover">
                                        </div>
                                    @else
                                        <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded border shadow-sm" style="width: 48px; height: 48px; font-size: 1.5rem;">
                                            <i class="bi bi-image text-secondary opacity-50"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold small text-dark">{{ $menu->name }}</div>
                                    <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $menu->description }}">{{ $menu->description ?? '-' }}</div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-secondary border border-secondary-subtle rounded-pill px-2 fw-medium">
                                        {{ $menu->category->name }}
                                    </span>
                                </td>
                                <td class="py-3 text-end fw-semibold small text-dark">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 text-center">
                                    @if($menu->stock > 10)
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">{{ $menu->stock }}</span>
                                    @elseif($menu->stock > 0)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">{{ $menu->stock }}</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">{{ $menu->stock }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @if($menu->is_active)
                                        <i class="bi bi-check-circle-fill text-success" title="Aktif"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger" title="Tidak Aktif"></i>
                                    @endif
                                </td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 me-1 text-secondary hover-bg-light">
                                        <i class="bi bi-pencil" style="font-size: 0.75rem;"></i> Edit
                                    </a>
                                    
                                    <a href="{{ route('admin.menus.destroy', $menu->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 text-danger hover-bg-light" data-confirm-delete="true">
                                        <i class="bi bi-trash" style="font-size: 0.75rem;"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-muted small">Tidak ada data menu.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $menus->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
