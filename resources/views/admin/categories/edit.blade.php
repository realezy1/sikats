@extends('layouts.app')

@section('header')
    Edit Kategori
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="custom-card p-4">
                <div class="d-flex align-items-center fw-bold text-dark fs-5 mb-4">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                    Edit Kategori
                </div>
                
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nama Kategori -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold text-dark small">Nama Kategori</label>
                        <input id="name" name="name" type="text" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required autofocus />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tipe Kategori -->
                    <div class="mb-5">
                        <label for="type" class="form-label fw-semibold text-dark small">Tipe Kategori</label>
                        <select id="type" name="type" class="form-select form-select-lg fs-6 @error('type') is-invalid @enderror" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="Makanan" {{ old('type', $category->type) == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ old('type', $category->type) == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light border rounded-pill px-4 fw-medium text-secondary hover-bg-light">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium">
                            <i class="bi bi-check2 me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
