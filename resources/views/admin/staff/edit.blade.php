@extends('layouts.admin')

@section('title', 'Edit Staf')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Staf</h3>
                <p class="text-subtitle text-muted">Edit informasi staf</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staf</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Staf</li>
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
                    <h4 class="card-title">Form Edit Staf</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="alert-heading">Terjadi kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $staff->name) }}" 
                                           placeholder="Masukkan nama lengkap" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $staff->email) }}" 
                                           placeholder="Masukkan email" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">Foto Staf</label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    
                                    @if($staff->photo && Storage::disk('public')->exists($staff->photo))
                                        <div class="mt-2">
                                            <label class="form-label">Foto Saat Ini:</label>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Storage::url($staff->photo) }}" 
                                                     alt="Foto {{ $staff->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <small class="text-muted">{{ basename($staff->photo) }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Peran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">Pilih peran</option>
                                        <option value="admin" {{ old('role', $staff->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="cashier" {{ old('role', $staff->role) == 'cashier' ? 'selected' : '' }}>Kasir</option>
                                        <option value="kitchen" {{ old('role', $staff->role) == 'kitchen' ? 'selected' : '' }}>Dapur</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password Baru <small class="text-muted">(kosongkan jika tidak ingin mengubah)</small></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="Minimal 8 karakter">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" name="is_active" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', $staff->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Staf Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Staf yang tidak aktif tidak dapat login ke sistem</small>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Staf -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Informasi Staf</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>ID:</strong> {{ $staff->id }}</p>
                                            <p><strong>Email:</strong> {{ $staff->email }}</p>
                                            <p><strong>Peran Saat Ini:</strong> 
                                                <span class="badge bg-{{ $staff->role == 'admin' ? 'primary' : ($staff->role == 'cashier' ? 'success' : ($staff->role == 'kitchen' ? 'warning' : 'info')) }}">
                                                    {{ ucfirst($staff->role) }}
                                                </span>
                                            </p>
                                            <p><strong>Status:</strong> 
                                                <span class="badge bg-{{ $staff->is_active ? 'success' : 'danger' }}">
                                                    {{ $staff->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Dibuat:</strong> {{ $staff->created_at->format('d M Y H:i') }}</p>
                                            <p><strong>Terakhir Update:</strong> {{ $staff->updated_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.staff.index') }}" class="btn btn-light-secondary">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </a>
                                    <div>
                                        <a href="{{ route('admin.staff.show', $staff->id) }}" class="btn btn-info me-2">
                                            <i class="bi bi-eye"></i> Lihat Detail
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save"></i> Update Staf
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        
        if (password && confirmPassword && password !== confirmPassword) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });

    // Show/hide password fields based on password input
    document.getElementById('password').addEventListener('input', function() {
        const confirmField = document.getElementById('password_confirmation');
        if (this.value) {
            confirmField.required = true;
            confirmField.parentElement.querySelector('label').innerHTML = 'Konfirmasi Password Baru <span class="text-danger">*</span>';
        } else {
            confirmField.required = false;
            confirmField.value = '';
            confirmField.parentElement.querySelector('label').innerHTML = 'Konfirmasi Password Baru';
        }
    });

    // Photo preview
    document.getElementById('photo').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create preview element if not exists
                let preview = document.getElementById('photo-preview-new');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'photo-preview-new';
                    preview.className = 'mt-2';
                    preview.innerHTML = '<label class="form-label">Preview Foto Baru:</label><div class="d-flex align-items-center"><img src="" class="rounded-circle me-2" style="width: 50px; height: 50px; object-fit: cover;"></div>';
                    document.getElementById('photo').parentNode.appendChild(preview);
                }
                
                const img = preview.querySelector('img');
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            // Remove preview if no file selected
            const preview = document.getElementById('photo-preview-new');
            if (preview) {
                preview.remove();
            }
        }
    });
</script>
@endpush 