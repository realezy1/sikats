@extends('layouts.app')

@section('header')
    Manajemen Meja & QR Code
@endsection

@section('content')
    <div class="row">
        <!-- Add New Table Form -->
        <div class="col-md-4 mb-4">
            <div class="custom-card p-4 h-100">
                <h5 class="fw-bold mb-3">Tambah Meja Baru</h5>
                <form action="{{ route('admin.tables.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="table_number" class="form-label text-muted small fw-bold">Nomor Meja</label>
                        <input type="number" class="form-control @error('table_number') is-invalid @enderror" id="table_number" name="table_number" value="{{ old('table_number') }}" required placeholder="Contoh: 11">
                        @error('table_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Meja
                    </button>
                </form>
            </div>
        </div>

        <!-- Table List -->
        <div class="col-md-8 mb-4">
            <div class="custom-card p-4 h-100">
                <h5 class="fw-bold mb-3">Daftar Meja</h5>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Meja</th>
                                <th>Token QR</th>
                                <th>QR Code</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tables as $table)
                                <tr>
                                    <td class="fw-bold fs-5 text-dark">#{{ $table->table_number }}</td>
                                    <td>
                                        <div class="text-muted small" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $table->qr_token }}">
                                            {{ $table->qr_token }}
                                        </div>
                                    </td>
                                    <td>
                                        <!-- Miniature QR Code -->
                                        @php
                                            $qrUrl = config('app.url') . '/order?table=' . $table->table_number . '&token=' . $table->qr_token;
                                        @endphp
                                        <div class="bg-white p-1 rounded border d-inline-block">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->generate($qrUrl) !!}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.tables.print', $table->id) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill mb-1">
                                            <i class="bi bi-printer"></i> Cetak
                                        </a>
                                        
                                        <form action="{{ route('admin.tables.reset-token', $table->id) }}" method="POST" class="d-inline-block form-reset">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-outline-warning rounded-pill mb-1 btn-reset" onclick="
                                                event.preventDefault();
                                                Swal.fire({
                                                    title: 'Reset Token QR?',
                                                    text: 'QR code lama otomatis tidak berlaku. Anda yakin?',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#ffc107',
                                                    cancelButtonColor: '#6c757d',
                                                    confirmButtonText: '<i class=\'bi bi-check-circle me-1\'></i> Ya, Reset!',
                                                    cancelButtonText: 'Batal'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        this.closest('form').submit();
                                                    }
                                                });
                                            ">
                                                <i class="bi bi-arrow-clockwise"></i> Reset
                                            </button>
                                        </form>

                                        <a href="{{ route('admin.tables.destroy', $table->id) }}" class="btn btn-sm btn-outline-danger rounded-pill mb-1" data-confirm-delete="true">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada meja.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
