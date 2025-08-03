@extends('layouts.admin')

@section('title', 'Tambah Meja - Anseyo Restaurant')

@section('page-title', 'Tambah Meja')
@section('page-subtitle', 'Tambah meja baru ke sistem')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.tables.index') }}">Manajemen Meja</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Meja</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Form Tambah Meja</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.tables.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="number" class="form-label">Nomor Meja <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" 
                                       id="number" name="number" value="{{ old('number') }}" 
                                       placeholder="Contoh: A1, B2, VIP1" required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Nomor unik untuk identifikasi meja</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="capacity" class="form-label">Kapasitas <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                       id="capacity" name="capacity" value="{{ old('capacity', 4) }}" 
                                       min="1" max="20" required>
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Jumlah maksimal orang yang bisa duduk</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Deskripsi tambahan tentang meja">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Informasi tambahan tentang lokasi atau karakteristik meja</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                   value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Meja Aktif
                            </label>
                        </div>
                        <small class="form-text text-muted">Meja yang tidak aktif tidak akan muncul di sistem pemesanan</small>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.tables.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Meja
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 