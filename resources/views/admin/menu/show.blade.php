@extends('layouts.admin')

@section('title', 'Detail Menu')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Menu</h3>
                <p class="text-subtitle text-muted">Informasi lengkap menu</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Menu</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Informasi Menu</h4>
                    <div class="card-actions">
                        <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($menu->image && Storage::disk('public')->exists($menu->image))
                                <img src="{{ Storage::url($menu->image) }}" 
                                     alt="{{ $menu->name }}" 
                                     class="rounded mb-3" 
                                     style="width: 200px; height: 200px; object-fit: cover;">
                            @else
                                <div class="rounded bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 200px; height: 200px;">
                                    <i class="bi bi-image text-white" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            
                            <h4 class="mb-1">{{ $menu->name }}</h4>
                            <p class="text-muted mb-3">{{ $menu->category->name ?? 'Kategori tidak tersedia' }}</p>
                            
                            <div class="mb-3">
                                <h3 class="text-primary">Rp {{ number_format($menu->price) }}</h3>
                            </div>
                            
                            <div class="mb-3">
                                @if($menu->is_available)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @endif
                                
                                @if($menu->is_featured)
                                    <span class="badge bg-warning ms-1">Menu Unggulan</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Nama Menu</label>
                                        <p class="form-control-static">{{ $menu->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Kategori</label>
                                        <p class="form-control-static">
                                            @if($menu->category)
                                                <span class="badge bg-info">{{ $menu->category->name }}</span>
                                            @else
                                                <span class="text-muted">Kategori tidak tersedia</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Harga</label>
                                        <p class="form-control-static">
                                            <strong class="text-primary">Rp {{ number_format($menu->price) }}</strong>
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Status</label>
                                        <p class="form-control-static">
                                            @if($menu->is_available)
                                                <span class="badge bg-success">Tersedia</span>
                                                <small class="text-muted d-block">Menu dapat dipesan</small>
                                            @else
                                                <span class="badge bg-danger">Tidak Tersedia</span>
                                                <small class="text-muted d-block">Menu tidak dapat dipesan</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Menu Unggulan</label>
                                        <p class="form-control-static">
                                            @if($menu->is_featured)
                                                <span class="badge bg-warning">Ya</span>
                                                <small class="text-muted d-block">Ditampilkan sebagai menu unggulan</small>
                                            @else
                                                <span class="badge bg-secondary">Tidak</span>
                                                <small class="text-muted d-block">Menu biasa</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Urutan</label>
                                        <p class="form-control-static">{{ $menu->sort_order ?? 0 }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Deskripsi</label>
                                        <p class="form-control-static">
                                            @if($menu->description)
                                                {{ $menu->description }}
                                            @else
                                                <span class="text-muted">Tidak ada deskripsi</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">ID Menu</label>
                                        <p class="form-control-static">{{ $menu->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Slug</label>
                                        <p class="form-control-static">{{ $menu->slug }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Dibuat Pada</label>
                                        <p class="form-control-static">{{ $menu->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Terakhir Diupdate</label>
                                        <p class="form-control-static">{{ $menu->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Riwayat Pesanan</h4>
                </div>
                <div class="card-body">
                    @if($menu->orderItems && $menu->orderItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Harga Satuan</th>
                                        <th>Subtotal</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menu->orderItems->take(10) as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $item->order->id) }}" class="text-primary">
                                                #{{ $item->order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $item->order->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price) }}</td>
                                        <td>Rp {{ number_format($item->subtotal) }}</td>
                                        <td>
                                            @switch($item->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                    @break
                                                @case('preparing')
                                                    <span class="badge bg-info">Dipersiapkan</span>
                                                    @break
                                                @case('ready')
                                                    <span class="badge bg-success">Siap</span>
                                                    @break
                                                @case('served')
                                                    <span class="badge bg-primary">Dihidangkan</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($menu->orderItems->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Menampilkan 10 pesanan terakhir dari {{ $menu->orderItems->count() }} total pesanan</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Belum ada pesanan untuk menu ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 