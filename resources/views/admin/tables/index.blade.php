@extends('layouts.admin')

@section('title', 'Manajemen Meja - Anseyo Restaurant')

@section('page-title', 'Manajemen Meja')
@section('page-subtitle', 'Kelola meja restoran dan QR Code')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manajemen Meja</li>
@endsection

@section('content')
    <div class="mb-2">
        <a href="{{ route('admin.tables.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Meja</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No. Meja</th>
                    <th>QR Code</th>
                    <th>Kapasitas</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tables as $table)
                <tr>
                    <td><strong>{{ $table->number }}</strong></td>
                    <td><span class="badge bg-info">{{ $table->qr_code }}</span></td>
                    <td><span class="badge bg-secondary">{{ $table->capacity }} orang</span></td>
                    <td>
                        @switch($table->status)
                            @case('available')<span class="badge bg-success">Tersedia</span>@break
                            @case('occupied')<span class="badge bg-warning">Terisi</span>@break
                            @case('reserved')<span class="badge bg-info">Dipesan</span>@break
                            @case('maintenance')<span class="badge bg-danger">Maintenance</span>@break
                            @default<span class="badge bg-secondary">{{ $table->status }}</span>
                        @endswitch
                    </td>
                    <td>{{ Str::limit($table->description, 50) ?: '-' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.tables.show', $table->id) }}" class="btn btn-sm btn-info" title="Lihat"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.tables.edit', $table->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <a href="{{ route('admin.tables.qr-code', $table->id) }}" class="btn btn-sm btn-success" title="QR Code"><i class="bi bi-qr-code"></i></a>
                            <form action="{{ route('admin.tables.destroy', $table->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus meja ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        <div class="py-4">
                            <i class="bi bi-table fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada meja yang ditambahkan</p>
                            <a href="{{ route('admin.tables.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Meja</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tables->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $tables->links() }}
    </div>
    @endif
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Ringkasan Status Meja</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon green me-3">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $statusOverview['available'] ?? 0 }}</h6>
                                    <small class="text-muted">Tersedia</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon orange me-3">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $statusOverview['occupied'] ?? 0 }}</h6>
                                    <small class="text-muted">Terisi</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon blue me-3">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $statusOverview['reserved'] ?? 0 }}</h6>
                                    <small class="text-muted">Dipesan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon red me-3">
                                    <i class="bi bi-tools"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $statusOverview['maintenance'] ?? 0 }}</h6>
                                    <small class="text-muted">Maintenance</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Auto refresh setiap 30 detik untuk update status meja
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endpush 