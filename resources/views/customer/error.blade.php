@extends('layouts.customer')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="text-center p-4">
        <div class="mb-4">
            <i class="bi bi-exclamation-circle text-danger" style="font-size: 4rem;"></i>
        </div>
        <h4 class="fw-bold text-dark mb-2">Akses Ditolak</h4>
        <p class="text-muted">{{ $message ?? 'Terjadi kesalahan.' }}</p>
    </div>
</div>
@endsection
