@extends('layouts.app')

@section('header')
    Buat Pesanan Baru
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="custom-card p-4">
                <div class="d-flex align-items-center fw-bold text-dark fs-5 mb-4">
                    <i class="bi bi-receipt me-2 text-primary"></i>
                    Detail Pesanan
                </div>
                
                <form action="{{ route('cashier.orders.store') }}" method="POST">
                    @csrf
                    
                    <!-- Meja -->
                    <div class="mb-4">
                        <label for="table_id" class="form-label fw-semibold text-dark small">Pilih Meja *</label>
                        <select id="table_id" name="table_id" class="form-select form-select-lg fs-6 @error('table_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Meja --</option>
                            @foreach($tables as $table)
                                <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }}>
                                    Meja {{ $table->table_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('table_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text text-muted mt-1">
                            <i class="bi bi-info-circle me-1"></i> Meja yang sedang melayani pesanan aktif tidak ditampilkan.
                        </div>
                    </div>

                    <!-- Nama Pelanggan -->
                    <div class="mb-5">
                        <label for="customer_name" class="form-label fw-semibold text-dark small">Nama Pelanggan (Opsional)</label>
                        <input id="customer_name" name="customer_name" type="text" class="form-control form-control-lg fs-6 @error('customer_name') is-invalid @enderror" value="{{ old('customer_name') }}" placeholder="Masukkan nama pelanggan" />
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="{{ route('cashier.orders.index') }}" class="btn btn-light border rounded-pill px-4 fw-medium text-secondary hover-bg-light">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium">
                            <i class="bi bi-arrow-right me-1"></i> Mulai Pesanan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
