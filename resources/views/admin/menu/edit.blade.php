@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Menu</h3>
                <p class="text-subtitle text-muted">Edit informasi menu</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Menu</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                    <h4 class="card-title">Edit Menu: {{ $menu->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                               id="price" name="price" value="{{ old('price', $menu->price) }}" 
                                               min="0" step="100" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order" class="form-label">Urutan</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', $menu->sort_order ?? 0) }}" 
                                           min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="form-label">Gambar Menu</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Status</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_available" 
                                                       name="is_available" value="1" 
                                                       {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_available">
                                                    Tersedia
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_featured" 
                                                       name="is_featured" value="1" 
                                                       {{ old('is_featured', $menu->is_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">
                                                    Menu Unggulan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Current Image Preview -->
                        @if($menu->image && Storage::disk('public')->exists($menu->image))
                            <div class="form-group">
                                <label class="form-label">Gambar Saat Ini</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($menu->image) }}" 
                                         alt="{{ $menu->name }}" 
                                         class="rounded me-3" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                    <div>
                                        <p class="mb-1"><strong>{{ $menu->name }}</strong></p>
                                        <small class="text-muted">{{ basename($menu->image) }}</small>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Menu
                            </button>
                            <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
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
// Image preview
document.getElementById('image').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('image-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'image-preview';
                preview.className = 'form-group mt-3';
                preview.innerHTML = '<label class="form-label">Preview Gambar Baru:</label><div class="d-flex align-items-center"><img src="" class="rounded me-3" style="width: 100px; height: 100px; object-fit: cover;"></div>';
                document.getElementById('image').parentNode.appendChild(preview);
            }
            const img = preview.querySelector('img');
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        const preview = document.getElementById('image-preview');
        if (preview) {
            preview.remove();
        }
    }
});

// Price formatting
document.getElementById('price').addEventListener('input', function() {
    let value = this.value.replace(/[^\d]/g, '');
    if (value) {
        this.value = parseInt(value);
    }
});
</script>
@endpush 