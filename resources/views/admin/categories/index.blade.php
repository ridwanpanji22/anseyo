@extends('layouts.admin')

@section('title', 'Kategori Menu')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kategori Menu</h3>
                <p class="text-subtitle text-muted">Kelola kategori menu restoran</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Kategori</li>
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
                    <h4 class="card-title">Daftar Kategori</h4>
                    <div class="card-actions">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Kategori
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Menu</th>
                                    <th>Urutan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $category->slug }}</small>
                                    </td>
                                    <td>
                                        @if($category->description)
                                            {{ Str::limit($category->description, 50) }}
                                        @else
                                            <span class="text-muted">Tidak ada deskripsi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->menus_count ?? 0 }}</span>
                                    </td>
                                    <td>{{ $category->sort_order ?? 0 }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Belum ada kategori menu</p>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle"></i> Tambah Kategori Pertama
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($categories->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 