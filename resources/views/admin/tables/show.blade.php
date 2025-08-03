@extends('layouts.admin')

@section('title', 'Detail Meja - Anseyo Restaurant')

@section('page-title', 'Detail Meja')
@section('page-subtitle', 'Informasi lengkap meja')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tables.index') }}">Manajemen Meja</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Meja</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Informasi Meja</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nomor Meja:</strong></td>
                                <td>{{ $table->number }}</td>
                            </tr>
                            <tr>
                                <td><strong>QR Code:</strong></td>
                                <td><span class="badge bg-info">{{ $table->qr_code }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Kapasitas:</strong></td>
                                <td>{{ $table->capacity }} orang</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @switch($table->status)
                                        @case('available')
                                            <span class="badge bg-success">Tersedia</span>
                                            @break
                                        @case('occupied')
                                            <span class="badge bg-warning">Terisi</span>
                                            @break
                                        @case('reserved')
                                            <span class="badge bg-info">Dipesan</span>
                                            @break
                                        @case('maintenance')
                                            <span class="badge bg-danger">Maintenance</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $table->status }}</span>
                                    @endswitch
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Status Aktif:</strong></td>
                                <td>
                                    @if($table->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Total Pesanan:</strong></td>
                                <td>{{ $table->orders_count ?? 0 }} pesanan</td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td>{{ $table->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Terakhir Update:</strong></td>
                                <td>{{ $table->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($table->description)
                <div class="mt-3">
                    <h6>Deskripsi:</h6>
                    <p class="text-muted">{{ $table->description }}</p>
                </div>
                @endif
                
                <div class="mt-4">
                    <a href="{{ route('admin.tables.edit', $table->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit Meja
                    </a>
                    <a href="{{ route('admin.tables.qr-code', $table->id) }}" class="btn btn-success">
                        <i class="bi bi-qr-code"></i> Lihat QR Code
                    </a>
                    <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4>Pesanan Terbaru</h4>
            </div>
            <div class="card-body">
                @if($table->orders && $table->orders->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($table->orders as $order)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $order->order_number }}</h6>
                                    <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">Rp {{ number_format($order->total) }}</span>
                                    <br>
                                    <small class="text-muted">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="text-warning">Menunggu</span>
                                                @break
                                            @case('preparing')
                                                <span class="text-info">Disiapkan</span>
                                                @break
                                            @case('ready')
                                                <span class="text-success">Siap</span>
                                                @break
                                            @case('served')
                                                <span class="text-primary">Disajikan</span>
                                                @break
                                            @case('completed')
                                                <span class="text-success">Selesai</span>
                                                @break
                                            @case('cancelled')
                                                <span class="text-danger">Dibatalkan</span>
                                                @break
                                        @endswitch
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                        <p class="mt-2 text-muted">Belum ada pesanan untuk meja ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 