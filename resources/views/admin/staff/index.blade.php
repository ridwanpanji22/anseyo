@extends('layouts.admin')

@section('title', 'Manajemen Staf - Anseyo Restaurant')

@section('page-title', 'Manajemen Staf')
@section('page-subtitle', 'Kelola data staf restoran')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manajemen Staf</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Daftar Staf</h4>
                <a href="{{ route('admin.staff.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Staf
                </a>
            </div>
            <div class="card-body">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($staffs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($staffs as $staff)
                                <tr>
                                    <td>
                                        @if($staff->photo && Storage::disk('public')->exists($staff->photo))
                                            <img src="{{ Storage::url($staff->photo) }}" 
                                                 alt="Foto {{ $staff->name }}" 
                                                 class="rounded-circle" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-person text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $staff->name }}</strong>
                                    </td>
                                    <td>{{ $staff->email }}</td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'admin' => 'primary',
                                                'cashier' => 'success',
                                                'kitchen' => 'warning',
                                                'waiter' => 'info',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $roleColors[$staff->role] ?? 'secondary' }}">
                                            {{ ucfirst($staff->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($staff->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.staff.edit', $staff->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus staf ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination removed for debugging -->
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-people" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3">Belum ada staf</h5>
                        <p class="text-muted">Silakan tambahkan staf baru untuk restoran Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 