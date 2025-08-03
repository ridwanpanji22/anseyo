@extends('layouts.admin')

@section('title', 'Tambah Staf Baru')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Staf Baru</h3>
                <p class="text-subtitle text-muted">Tambah staf baru ke sistem</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.staff.index') }}">Staf</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Staf</li>
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
                    <h4 class="card-title">Form Tambah Staf</h4>
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

                    <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
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
                                           id="email" name="email" value="{{ old('email') }}" 
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
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Peran <span class="text-danger">*</span></label>
                                    <select class="form-control @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">Pilih peran</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Kasir</option>
                                        <option value="kitchen" {{ old('role') == 'kitchen' ? 'selected' : '' }}>Dapur</option>
                                        <option value="waiter" {{ old('role') == 'waiter' ? 'selected' : '' }}>Pelayan</option>
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
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="Minimal 8 karakter" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Ulangi password" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" name="is_active" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                        <label class="form-check-label" for="is_active">
                                            Staf Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Staf yang tidak aktif tidak dapat login ke sistem</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.staff.index') }}" class="btn btn-light-secondary">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Simpan Staf
                                    </button>
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
        
        if (password !== confirmPassword) {
            this.setCustomValidity('Password tidak cocok');
        } else {
            this.setCustomValidity('');
        }
    });

    // Photo preview
    document.getElementById('photo').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create preview element if not exists
                let preview = document.getElementById('photo-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'photo-preview';
                    preview.className = 'mt-2';
                    preview.innerHTML = '<label class="form-label">Preview:</label><div class="d-flex align-items-center"><img src="" class="rounded-circle me-2" style="width: 50px; height: 50px; object-fit: cover;"></div>';
                    document.getElementById('photo').parentNode.appendChild(preview);
                }
                
                const img = preview.querySelector('img');
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        } else {
            // Remove preview if no file selected
            const preview = document.getElementById('photo-preview');
            if (preview) {
                preview.remove();
            }
        }
    });
</script>
@endpush 