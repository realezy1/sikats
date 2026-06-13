@extends('layouts.app')

@section('header')
    Kategori
@endsection

@section('content')
    


    <div class="row">
        <div class="col-12">
            <div class="custom-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center fw-bold text-dark fs-5">
                        <i class="bi bi-tags me-2 text-muted"></i>
                        Daftar Kategori
                    </div>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                    </a>
                </div>

                <!-- Search Bar -->
                <form action="{{ route('admin.categories.index') }}" method="GET" class="mb-4">
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari nama atau tipe kategori..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                        @endif
                    </div>
                </form>

                @if(request('search'))
                    <div class="alert alert-light border py-2 px-3 small mb-3">
                        <i class="bi bi-info-circle me-1"></i> Menampilkan hasil pencarian untuk "<strong>{{ request('search') }}</strong>"
                        — {{ $categories->total() }} data ditemukan.
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0 text-nowrap">
                        <thead>
                            <tr class="text-muted small border-bottom">
                                <th class="pb-3 fw-medium">Nama</th>
                                <th class="pb-3 fw-medium">Tipe</th>
                                <th class="pb-3 fw-medium text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($categories as $category)
                            <tr>
                                <td class="py-3">
                                    <div class="fw-semibold small text-dark">{{ $category->name }}</div>
                                </td>
                                <td class="py-3">
                                    @if($category->type == 'Makanan')
                                        <span class="badge bg-warning text-dark border border-warning-subtle rounded-pill px-2">
                                            <i class="bi bi-egg-fried me-1"></i>{{ $category->type }}
                                        </span>
                                    @else
                                        <span class="badge bg-info text-dark border border-info-subtle rounded-pill px-2">
                                            <i class="bi bi-cup-straw me-1"></i>{{ $category->type }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 me-1 text-secondary hover-bg-light">
                                        <i class="bi bi-pencil" style="font-size: 0.75rem;"></i> Edit
                                    </a>
                                    
                                    <a href="{{ route('admin.categories.destroy', $category->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 text-danger hover-bg-light" data-confirm-delete="true">
                                        <i class="bi bi-trash" style="font-size: 0.75rem;"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-muted small">Tidak ada data kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
