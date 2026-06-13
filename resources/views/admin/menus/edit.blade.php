@extends('layouts.app')

@php
    /** @var \App\Models\Menu $menu */
    /** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Category[] $categories */
@endphp

@section('header')
    Edit Menu
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="custom-card p-4">
                <div class="d-flex align-items-center fw-bold text-dark fs-5 mb-4">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                    Edit Data Menu
                </div>
                
                <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nama Menu -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold text-dark small">Nama Menu</label>
                        <input id="name" name="name" type="text" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name', $menu->name) }}" required autofocus />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label fw-semibold text-dark small">Kategori</label>
                        <select id="category_id" name="category_id" class="form-select form-select-lg fs-6 @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->type }})
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold text-dark small">Deskripsi Singkat</label>
                        <textarea id="description" name="description" rows="3" class="form-control form-control-lg fs-6 @error('description') is-invalid @enderror" placeholder="Deskripsikan menu ini...">{{ old('description', $menu->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Harga & Stok -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="price" class="form-label fw-semibold text-dark small">Harga (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-secondary">Rp</span>
                                <input id="price" name="price" type="number" step="0.01" class="form-control form-control-lg fs-6 border-start-0 ps-0 @error('price') is-invalid @enderror" value="{{ old('price', $menu->price) }}" required min="0" oninput="if(this.value < 0) this.value = 0;" />
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-semibold text-dark small">Stok Tersedia</label>
                            <input id="stock" name="stock" type="number" class="form-control form-control-lg fs-6 @error('stock') is-invalid @enderror" value="{{ old('stock', $menu->stock) }}" required min="0" oninput="if(this.value < 0) this.value = 0;" />
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Foto -->
                    <div class="mb-4">
                        <label for="photo" class="form-label fw-semibold text-dark small">Foto Baru (Opsional)</label>
                        @if($menu->photo)
                            <div class="mb-3 d-flex align-items-center bg-light p-2 border rounded-3" style="width: fit-content;">
                                <img src="{{ Storage::url($menu->photo) }}" alt="Current Photo" class="rounded object-fit-cover me-3" style="width: 60px; height: 60px;">
                                <div class="small">
                                    <div class="fw-semibold text-dark">Foto Saat Ini</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Akan diganti jika Anda memilih berkas baru.</div>
                                </div>
                            </div>
                        @endif
                        <input type="file" id="photo" name="photo" accept="image/jpeg, image/png, image/webp" class="form-control form-control-lg fs-6 @error('photo') is-invalid @enderror" />
                        <div class="form-text mt-2 small text-muted"><i class="bi bi-info-circle me-1"></i> Format didukung: JPG, PNG, WEBP (Max 2MB).</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="mb-5 bg-light p-3 rounded-3 border">
                        <div class="form-check form-switch d-flex align-items-center mb-0">
                            <input class="form-check-input mt-0 me-3" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $menu->is_active) ? 'checked' : '' }} style="width: 2.5rem; height: 1.25rem; cursor: pointer;">
                            <label class="form-check-label fw-semibold text-dark mb-0" style="cursor: pointer;" for="is_active">
                                Menu Aktif <span class="text-muted fw-normal small ms-2">(Dapat dipesan oleh pelanggan)</span>                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-light border rounded-pill px-4 fw-medium text-secondary hover-bg-light">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium">
                            <i class="bi bi-check2 me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
