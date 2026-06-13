@extends('layouts.app')

@section('header')
    Manajemen Pegawai
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custom-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center fw-bold text-dark fs-5">
                        <i class="bi bi-people me-2 text-muted"></i>
                        Daftar Pegawai
                    </div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">
                        <i class="bi bi-person-plus me-1"></i> Tambah Pegawai
                    </a>
                </div>

                <!-- Filter Role -->
                <div class="d-flex gap-2 mb-4 flex-wrap">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm rounded-pill px-3 fw-medium {{ !request('role') ? 'btn-dark' : 'btn-outline-secondary' }}">
                        Semua
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 1]) }}" class="btn btn-sm rounded-pill px-3 fw-medium {{ request('role') == 1 ? 'btn-danger' : 'btn-outline-danger' }}">
                        Admin
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 2]) }}" class="btn btn-sm rounded-pill px-3 fw-medium {{ request('role') == 2 ? 'btn-primary' : 'btn-outline-primary' }}">
                        Kasir
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 3]) }}" class="btn btn-sm rounded-pill px-3 fw-medium {{ request('role') == 3 ? 'btn-info' : 'btn-outline-info' }}">
                        Dapur
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0 text-nowrap">
                        <thead>
                            <tr class="text-muted small border-bottom">
                                <th class="pb-3 fw-medium">Nama </th>
                                <th class="pb-3 fw-medium">Email</th>
                                <th class="pb-3 fw-medium">Role </th>
                                <th class="pb-3 fw-medium">No. HP</th>
                                <th class="pb-3 fw-medium text-center">Status</th>
                                <th class="pb-3 fw-medium text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @forelse($users as $user)
                            <tr>
                                <td class="py-3">
                                    <div class="fw-semibold small text-dark">{{ $user->name }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="small text-muted">{{ $user->email }}</div>
                                </td>
                                <td class="py-3">
                                    @if($user->role_id == 1)
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 fw-medium">Admin</span>
                                    @elseif($user->role_id == 2)
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 fw-medium">Kasir</span>
                                    @elseif($user->role_id == 3)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 fw-medium">Dapur</span>
                                    @endif
                                </td>
                                <td class="py-3 small text-muted">
                                    {{ $user->mobile_number ?? '-' }}
                                </td>
                                <td class="py-3 text-center">
                                    @if($user->is_active)
                                        <i class="bi bi-check-circle-fill text-success" title="Aktif"></i>
                                    @else
                                        <i class="bi bi-x-circle-fill text-danger" title="Tidak Aktif"></i>
                                    @endif
                                </td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 me-1 text-secondary hover-bg-light">
                                        <i class="bi bi-pencil" style="font-size: 0.75rem;"></i> Edit
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                    <a href="{{ route('admin.users.destroy', $user->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 text-danger hover-bg-light" data-confirm-delete="true">
                                        <i class="bi bi-trash" style="font-size: 0.75rem;"></i> Hapus
                                    </a>
                                    @else
                                    <button class="btn btn-sm btn-light border rounded-pill px-3 text-muted" disabled title="Anda tidak dapat menghapus diri sendiri">
                                        <i class="bi bi-trash" style="font-size: 0.75rem;"></i> Hapus
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-4 text-center text-muted small">Tidak ada data pegawai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
