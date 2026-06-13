@extends('layouts.app')

@section('header')
    Laporan Penjualan
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0 text-dark">Laporan & Analitik</h4>
        <form action="{{ route('admin.reports.pdf') }}" method="GET" target="_blank">
            <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
            <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-file-earmark-pdf me-2"></i>Cetak PDF
            </button>
        </form>
    </div>

    <!-- Date Filter Card -->
    <div class="custom-card p-4 mb-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="start_date" class="form-label text-muted small fw-bold text-uppercase">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-4">
                <label for="end_date" class="form-label text-muted small fw-bold text-uppercase">Tanggal Akhir</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-dark px-4 w-100">
                    <i class="bi bi-funnel me-2"></i>Filter Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Metrics -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="custom-card p-4 h-100 border-start border-primary border-4">
                <div class="text-muted small fw-bold text-uppercase mb-1">Total Omset</div>
                <h3 class="fw-bolder text-dark mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="custom-card p-4 h-100 border-start border-success border-4">
                <div class="text-muted small fw-bold text-uppercase mb-1">Total Transaksi</div>
                <h3 class="fw-bolder text-dark mb-0">{{ number_format($totalTransactions, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="custom-card p-4 h-100 border-start border-info border-4">
                <div class="text-muted small fw-bold text-uppercase mb-1">Rata-rata Transaksi</div>
                <h3 class="fw-bolder text-dark mb-0">Rp {{ number_format($avgTransaction, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="custom-card p-3 h-100 bg-light border-0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted small fw-bold">Tunai</span>
                    <span class="fw-bold text-success">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</span>
                </div>
                <hr class="my-2 border-secondary opacity-25">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small fw-bold">Midtrans</span>
                    <span class="fw-bold text-primary">Rp {{ number_format($midtransRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="custom-card p-4 h-100">
                <h5 class="fw-bold mb-4">Tren Penjualan <span class="text-muted fs-6 fw-normal">({{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }})</span></h5>
                <div style="height: 300px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Selling Items -->
        <div class="col-lg-4">
            <div class="custom-card p-4 h-100">
                <h5 class="fw-bold mb-4">Menu Terlaris</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topItems as $item)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $item->menu->name }}</td>
                                    <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $item->total_qty }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-3 text-muted">Belum ada data penjualan menu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Omset Penjualan (Rp)',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#0d6efd',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f0f0f0'
                        },
                        ticks: {
                            callback: function(value, index, values) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + ' Jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
