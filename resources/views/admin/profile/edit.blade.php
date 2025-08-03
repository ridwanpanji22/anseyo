@extends('layouts.admin')

@section('title', 'Edit Profil - Anseyo Restaurant')

@section('page-title', 'Edit Profil')
@section('page-subtitle', 'Update informasi profil Anda')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Edit Profil</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Form Edit Profil</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Profile Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                       placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                                       placeholder="Masukkan email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current User Info -->
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Role:</strong> 
                                <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong> 
                                @if(auth()->user()->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Bergabung Sejak:</strong> {{ auth()->user()->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Password Change Section -->
                    <h5 class="mb-3">Ubah Password (Opsional)</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" 
                                       placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="new_password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                       id="new_password" name="new_password" 
                                       placeholder="Masukkan password baru">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" 
                                       id="new_password_confirmation" name="new_password_confirmation" 
                                       placeholder="Konfirmasi password baru">
                                <small class="form-text text-muted">Ulangi password baru</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Profil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Password confirmation validation
document.getElementById('new_password_confirmation').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (newPassword && confirmPassword && newPassword !== confirmPassword) {
        this.setCustomValidity('Password tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('new_password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('new_password_confirmation');
    if (confirmPassword.value) {
        confirmPassword.dispatchEvent(new Event('input'));
    }
});

// Show/hide password fields
document.addEventListener('DOMContentLoaded', function() {
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    
    // If current password is filled, make new password required
    currentPassword.addEventListener('input', function() {
        if (this.value) {
            newPassword.required = true;
            confirmPassword.required = true;
        } else {
            newPassword.required = false;
            confirmPassword.required = false;
        }
    });
    
    // If new password is filled, make current password required
    newPassword.addEventListener('input', function() {
        if (this.value) {
            currentPassword.required = true;
            confirmPassword.required = true;
        } else {
            currentPassword.required = false;
            confirmPassword.required = false;
        }
    });
});
</script>
@endpush 