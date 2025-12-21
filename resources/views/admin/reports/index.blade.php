@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Laporan Penjualan</h3>
                <p class="text-subtitle text-muted">Analisis penjualan dan performa restoran</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <!-- Filter Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filter Periode</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="period" class="form-label">Periode</label>
                            <select class="form-select" id="period" name="period" onchange="this.form.submit()">
                                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Kustom</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="custom-date-start" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ request('start_date') }}" onchange="this.form.submit()">
                        </div>
                        
                        <div class="col-md-3" id="custom-date-end" style="{{ request('period') == 'custom' ? '' : 'display: none;' }}">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ request('end_date') }}" onchange="this.form.submit()">
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                            <a href="{{ route('admin.reports.export-pdf', request()->all()) }}" class="btn btn-danger" target="_blank">
                                 Export PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon purple mb-2">
                                <i class="bi bi-cart-check"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Pesanan</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($totalOrders ?? 0) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon blue mb-2">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Total Pendapatan</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($totalRevenue ?? 0) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon green mb-2">
                                <i class="bi bi-graph-up"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Rata-rata Pesanan</h6>
                            <h6 class="font-extrabold mb-0">Rp {{ number_format($averageOrderValue ?? 0) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body px-4 py-4-5">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                            <div class="stats-icon red mb-2">
                                <i class="bi bi-clock"></i>
                            </div>
                        </div>
                        <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                            <h6 class="text-muted font-semibold">Pesanan Pending</h6>
                            <h6 class="font-extrabold mb-0">{{ number_format($pendingOrders ?? 0) }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Laporan</h4>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="bi bi-graph-up text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Halaman laporan akan menampilkan data statistik penjualan</p>
                        <p class="text-muted">Fitur ini akan menampilkan:</p>
                        <ul class="text-muted text-start">
                            <li>Menu terlaris</li>
                            <li>Status pesanan</li>
                            <li>Grafik penjualan</li>
                            <li>Export PDF/Excel</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.getElementById('period').addEventListener('change', function() {
    const customFields = document.querySelectorAll('#custom-date-start, #custom-date-end');
    if (this.value === 'custom') {
        customFields.forEach(field => field.style.display = 'block');
    } else {
        customFields.forEach(field => field.style.display = 'none');
    }
});
</script>
@endpush 