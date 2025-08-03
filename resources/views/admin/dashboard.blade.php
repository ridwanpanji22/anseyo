@extends('layouts.admin')

@section('title', 'Dashboard Admin - Anseyo Restaurant')

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di dashboard admin Anseyo Restaurant')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
@endsection

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-12">
        <div class="row">
            <!-- Total Orders -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon purple mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Total Pesanan</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($totalOrders) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today's Orders -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon blue mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pesanan Hari Ini</h6>
                                <h6 class="font-extrabold mb-0">{{ number_format($todayOrders) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Today's Revenue -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon green mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Pendapatan Hari Ini</h6>
                                <h6 class="font-extrabold mb-0">Rp {{ number_format($todayRevenue) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Available Tables -->
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon red mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-table"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Meja Tersedia</h6>
                                <h6 class="font-extrabold mb-0">{{ $availableTables }}/{{ $totalTables }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Status Cards -->
    <div class="col-12">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon orange mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-clock"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Menunggu</h6>
                                <h6 class="font-extrabold mb-0">{{ $pendingOrders }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon yellow mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-gear"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Sedang Disiapkan</h6>
                                <h6 class="font-extrabold mb-0">{{ $preparingOrders }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon green mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Siap Disajikan</h6>
                                <h6 class="font-extrabold mb-0">{{ $readyOrders }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-center align-items-center">
                                <div class="stats-icon blue mb-2 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-list-ul"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                <h6 class="text-muted font-semibold">Menu Tersedia</h6>
                                <h6 class="font-extrabold mb-0">{{ $availableMenus }}/{{ $totalMenus }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders and Top Menus -->
    <div class="col-12">
        <div class="row">
            <!-- Recent Orders -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Pesanan Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Pesanan</th>
                                        <th>Meja</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->table->number }}</td>
                                        <td>
                                            @switch($order->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                    @break
                                                @case('preparing')
                                                    <span class="badge bg-info">Disiapkan</span>
                                                    @break
                                                @case('ready')
                                                    <span class="badge bg-success">Siap</span>
                                                    @break
                                                @case('served')
                                                    <span class="badge bg-primary">Disajikan</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-secondary">Selesai</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>Rp {{ number_format($order->total) }}</td>
                                        <td>{{ $order->created_at->format('H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada pesanan terbaru</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top Selling Menus -->
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Menu Terlaris</h4>
                    </div>
                    <div class="card-body">
                        @forelse($topMenus as $menu)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md me-3">
                                @if($menu->image)
                                    <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}">
                                @else
                                    <img src="{{ asset('assets/mazer/png/1.png') }}" alt="{{ $menu->name }}">
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $menu->name }}</h6>
                                <small class="text-muted">{{ $menu->order_items_count }} pesanan</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">{{ $menu->order_items_count }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-muted">Tidak ada data menu</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 