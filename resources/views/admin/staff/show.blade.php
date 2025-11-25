@extends('layouts.admin')

@section('title', 'Detail Staff')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Staff</h3>
                <p class="text-subtitle text-muted">Informasi lengkap staff</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staff</a></li>
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
                    <h4 class="card-title">Informasi Staff</h4>
                    <div class="card-actions">
                        <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($staff->photo && Storage::disk('public')->exists($staff->photo))
                                <img src="{{ Storage::url($staff->photo) }}" 
                                     alt="Foto {{ $staff->name }}" 
                                     class="rounded-circle mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 150px; height: 150px;">
                                    <i class="bi bi-person text-white" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                            
                            <h5 class="mb-1">{{ $staff->name }}</h5>
                            <p class="text-muted mb-3">{{ $staff->email }}</p>
                            
                            <div class="mb-3">
                                @switch($staff->role)
                                    @case('admin')
                                        <span class="badge bg-primary">Admin</span>
                                        @break
                                    @case('cashier')
                                        <span class="badge bg-info">Cashier</span>
                                        @break
                                    @case('kitchen')
                                        <span class="badge bg-warning">Kitchen</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($staff->role) }}</span>
                                @endswitch
                            </div>
                            
                            <div class="mb-3">
                                @if($staff->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                        <p class="form-control-static">{{ $staff->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Email</label>
                                        <p class="form-control-static">{{ $staff->email }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Role</label>
                                        <p class="form-control-static">
                                            @switch($staff->role)
                                                @case('admin')
                                                    <span class="badge bg-primary">Admin</span>
                                                    @break
                                                @case('cashier')
                                                    <span class="badge bg-info">Cashier</span>
                                                    @break
                                                @case('kitchen')
                                                    <span class="badge bg-warning">Kitchen</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($staff->role) }}</span>
                                            @endswitch
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Status</label>
                                        <p class="form-control-static">
                                            @if($staff->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                                <small class="text-muted d-block">Staff dapat login ke sistem</small>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                                <small class="text-muted d-block">Staff tidak dapat login ke sistem</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">ID Staff</label>
                                        <p class="form-control-static">{{ $staff->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Tanggal Bergabung</label>
                                        <p class="form-control-static">{{ $staff->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Terakhir Diupdate</label>
                                        <p class="form-control-static">{{ $staff->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Email Verified</label>
                                        <p class="form-control-static">
                                            @if($staff->email_verified_at)
                                                <span class="badge bg-success">Terverifikasi</span>
                                                <small class="text-muted d-block">{{ $staff->email_verified_at->format('d M Y H:i') }}</small>
                                            @else
                                                <span class="badge bg-warning">Belum Terverifikasi</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 