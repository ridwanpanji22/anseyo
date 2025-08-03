@extends('layouts.admin')

@section('title', 'Tambah Menu - Anseyo Restaurant')

@section('page-title', 'Tambah Menu')
@section('page-subtitle', 'Tambah menu baru ke restoran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Menu</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Form Tambah Menu</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label">Nama Menu <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" 
                                        name="category_id" 
                                        required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="price" class="form-label">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price') }}" 
                                           min="0" 
                                           step="100" 
                                           required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="sort_order" class="form-label">Urutan</label>
                                <input type="number" 
                                       class="form-control @error('sort_order') is-invalid @enderror" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       value="{{ old('sort_order', 0) }}" 
                                       min="0">
                                @error('sort_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="image" class="form-label">Gambar Menu</label>
                                <input type="file" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       accept="image/*">
                                <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_available" 
                                           name="is_available" 
                                           value="1" 
                                           {{ old('is_available', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_available">
                                        Tersedia
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="is_featured" 
                                           name="is_featured" 
                                           value="1" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Menu Unggulan
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 