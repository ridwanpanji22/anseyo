@extends('layouts.admin')

@section('title', 'Manajemen Menu - Anseyo Restaurant')

@section('page-title', 'Manajemen Menu')
@section('page-subtitle', 'Kelola menu restoran')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Menu</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Daftar Menu</h4>
                <a href="{{ route('admin.menu.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Tambah Menu
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $index => $menu)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($menu->image)
                                        <img src="{{ asset('storage/' . $menu->image) }}" 
                                             alt="{{ $menu->name }}" 
                                             class="rounded" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('assets/mazer/png/1.png') }}" 
                                             alt="{{ $menu->name }}" 
                                             class="rounded" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $menu->name }}</h6>
                                        @if($menu->description)
                                            <small class="text-muted">{{ Str::limit($menu->description, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $menu->category->name }}</span>
                                </td>
                                <td>Rp {{ number_format($menu->price) }}</td>
                                <td>
                                    @if($menu->is_available)
                                        <span class="badge bg-success">Tersedia</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    @if($menu->is_featured)
                                        <span class="badge bg-warning">Featured</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.menu.show', $menu->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.menu.edit', $menu->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.menu.destroy', $menu->id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data menu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($menus->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $menus->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 