@extends('layouts.admin')

@section('title', 'Manajemen Pesanan - Anseyo Restaurant')

@section('page-title', 'Manajemen Pesanan')
@section('page-subtitle', 'Kelola semua pesanan pelanggan')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Manajemen Pesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Pesanan</h4>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari no. pesanan, meja, atau pemesan...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Menyiapkan</option>
                                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Siap</option>
                                <option value="served" {{ request('status') == 'served' ? 'selected' : '' }}>Disajikan</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="payment_status">
                                <option value="">Semua Pembayaran</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                                <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Bayar Sebagian</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                    
                    <!-- Advanced Filters -->
                    <div class="row g-3 mt-2">
                        <div class="col-md-2">
                            <label class="form-label small">Dari Tanggal:</label>
                            <input type="date" 
                                   class="form-control form-control-sm" 
                                   name="date_from" 
                                   value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Sampai Tanggal:</label>
                            <input type="date" 
                                   class="form-control form-control-sm" 
                                   name="date_to" 
                                   value="{{ request('date_to') }}">
                        </div>
                    </div>
                </form>

                <!-- Search Results Info -->
                @if(request('search') || request('status') || request('payment_status') || request('date_from') || request('date_to'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Hasil Pencarian:</strong>
                        @if(request('search'))
                            <span class="badge bg-primary me-2">Kata kunci: "{{ request('search') }}"</span>
                        @endif
                        @if(request('status'))
                            <span class="badge bg-warning me-2">Status: {{ ucfirst(request('status')) }}</span>
                        @endif
                        @if(request('payment_status'))
                            <span class="badge bg-info me-2">Pembayaran: {{ ucfirst(request('payment_status')) }}</span>
                        @endif
                        @if(request('date_from') || request('date_to'))
                            <span class="badge bg-secondary me-2">
                                Periode: {{ request('date_from', 'Semua') }} - {{ request('date_to', 'Semua') }}
                            </span>
                        @endif
                        <span class="badge bg-success">{{ $orders->total() }} pesanan ditemukan</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Meja</th>
                                    <th>Pemesan</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Waktu Pesanan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $order->table->number ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($order->customer_name)
                                            <strong>{{ $order->customer_name }}</strong>
                                            @if($order->customer_phone)
                                                <br><small class="text-muted">{{ $order->customer_phone }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            @foreach($order->orderItems->take(2) as $item)
                                                {{ $item->menu_name }} ({{ $item->quantity }}x)<br>
                                            @endforeach
                                            @if($order->orderItems->count() > 2)
                                                <span class="text-muted">+{{ $order->orderItems->count() - 2 }} item lainnya</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'preparing' => 'info',
                                                'ready' => 'primary',
                                                'served' => 'success',
                                                'completed' => 'secondary',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'Pending',
                                                'preparing' => 'Menyiapkan',
                                                'ready' => 'Siap',
                                                'served' => 'Disajikan',
                                                'completed' => 'Selesai',
                                                'cancelled' => 'Dibatalkan'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] }}">
                                            {{ $statusLabels[$order->status] }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentColors = [
                                                'unpaid' => 'danger',
                                                'partial' => 'warning',
                                                'paid' => 'success'
                                            ];
                                            $paymentLabels = [
                                                'unpaid' => 'Belum Bayar',
                                                'partial' => 'Bayar Sebagian',
                                                'paid' => 'Lunas'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $paymentColors[$order->payment_status] }}">
                                            {{ $paymentLabels[$order->payment_status] }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit Pesanan">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-cart-x" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3">Belum ada pesanan</h5>
                        <p class="text-muted">Pesanan akan muncul di sini setelah pelanggan melakukan pemesanan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pesanan</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="preparing">Menyiapkan</option>
                            <option value="ready">Siap</option>
                            <option value="served">Disajikan</option>
                            <option value="completed">Selesai</option>
                            <option value="cancelled">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="unpaid">Belum Bayar</option>
                            <option value="partial">Bayar Sebagian</option>
                            <option value="paid">Lunas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh every 30 seconds (only if no search/filter is active)
    @if(!request('search') && !request('status') && !request('payment_status') && !request('date_from') && !request('date_to'))
    setInterval(function() {
        location.reload();
    }, 30000);
    @endif

    // Highlight search terms in table
    @if(request('search'))
    const searchTerm = '{{ request('search') }}';
    const tableCells = document.querySelectorAll('#ordersTable tbody td');
    
    tableCells.forEach(cell => {
        const text = cell.textContent;
        if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
            cell.style.backgroundColor = '#fff3cd';
            cell.style.fontWeight = 'bold';
        }
    });
    @endif
});
</script>
@endpush 