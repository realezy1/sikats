@extends('layouts.app')

@section('header')
    Edit Pegawai
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="custom-card p-4">
                <div class="d-flex align-items-center fw-bold text-dark fs-5 mb-4">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                    Edit Data Pegawai
                </div>
                
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nama -->
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold text-dark small">Nama Lengkap</label>
                        <input id="name" name="name" type="text" class="form-control form-control-lg fs-6 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus placeholder="Masukkan nama lengkap" />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold text-dark small">Alamat Email (Untuk Login)</label>
                        <input id="email" name="email" type="email" class="form-control form-control-lg fs-6 bg-light text-muted" value="{{ old('email', $user->email) }}" readonly />
                        <div class="form-text mt-2 small text-muted"><i class="bi bi-info-circle me-1"></i> Email tidak bisa diubah demi keamanan sistem.</div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold text-dark small">Password Baru (Opsional)</label>
                        <div class="position-relative">
                            <input id="password" name="password" type="password" class="form-control form-control-lg fs-6 pe-5 @error('password') is-invalid @enderror" autocomplete="new-password" placeholder="Minimal 8 karakter" />
                            <div class="position-absolute top-50 end-0 translate-middle-y pe-3" style="z-index: 10;">
                                <button type="button" id="togglePassword" class="btn btn-link text-secondary p-0 text-decoration-none">
                                    <svg id="eyeIcon" height="20" width="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="form-text mt-2 small text-muted"><i class="bi bi-info-circle me-1"></i> Kosongkan kolom ini jika Anda tidak ingin mengubah password lama.</div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="mb-4">
                        <label for="role_id" class="form-label fw-semibold text-dark small">Posisi / Role</label>
                        <select id="role_id" name="role_id" class="form-select form-select-lg fs-6 @error('role_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Posisi --</option>
                            <option value="1" {{ old('role_id', $user->role_id) == 1 ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ old('role_id', $user->role_id) == 2 ? 'selected' : '' }}>Kasir</option>
                            <option value="3" {{ old('role_id', $user->role_id) == 3 ? 'selected' : '' }}>Dapur</option>
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mobile Number -->
                    <div class="mb-4">
                        <label for="mobile_number" class="form-label fw-semibold text-dark small">Nomor HP</label>
                        <input id="mobile_number" name="mobile_number" type="text" class="form-control form-control-lg fs-6 @error('mobile_number') is-invalid @enderror" value="{{ old('mobile_number', $user->mobile_number) }}" placeholder="Contoh: 08123456789" />
                        @error('mobile_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold text-dark small">Alamat</label>
                        <textarea id="address" name="address" rows="3" class="form-control form-control-lg fs-6 @error('address') is-invalid @enderror" placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="mb-5 bg-light p-3 rounded-3 border">
                        <div class="form-check form-switch d-flex align-items-center mb-0">
                            <input class="form-check-input mt-0 me-3" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} style="width: 2.5rem; height: 1.25rem; cursor: pointer;">
                            <label class="form-check-label fw-semibold text-dark mb-0" style="cursor: pointer;" for="is_active">
                                Akun Aktif <span class="text-muted fw-normal small ms-2">(Hanya akun aktif yang bisa login ke sistem)</span>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light border rounded-pill px-4 fw-medium text-secondary hover-bg-light">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium">
                            <i class="bi bi-check2 me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            if (togglePassword) {
                togglePassword.addEventListener('click', function () {
                    const passwordInput = document.getElementById('password');
                    const eyeIcon = document.getElementById('eyeIcon');
                    
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    if (type === 'text') {
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
                    } else {
                        eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                    }
                });
            }
        });
    </script>
@endsection
