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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Daftar Pesanan</h4>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="statusFilter" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="preparing">Menyiapkan</option>
                        <option value="ready">Siap</option>
                        <option value="served">Disajikan</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <select class="form-select form-select-sm" id="paymentFilter" style="width: auto;">
                        <option value="">Semua Pembayaran</option>
                        <option value="unpaid">Belum Bayar</option>
                        <option value="partial">Bayar Sebagian</option>
                        <option value="paid">Lunas</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
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
                                        <span class="badge bg-primary">{{ $order->table->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <small>
                                            @foreach($order->orderItems->take(2) as $item)
                                                {{ $item->menu->name }} ({{ $item->quantity }}x)<br>
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
    // Status filter
    document.getElementById('statusFilter').addEventListener('change', function() {
        const status = this.value;
        const rows = document.querySelectorAll('#ordersTable tbody tr');
        
        rows.forEach(row => {
            const statusCell = row.querySelector('td:nth-child(5) .badge');
            if (status === '' || statusCell.textContent.toLowerCase().includes(status)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Payment filter
    document.getElementById('paymentFilter').addEventListener('change', function() {
        const payment = this.value;
        const rows = document.querySelectorAll('#ordersTable tbody tr');
        
        rows.forEach(row => {
            const paymentCell = row.querySelector('td:nth-child(6) .badge');
            if (payment === '' || paymentCell.textContent.toLowerCase().includes(payment)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Auto refresh every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
});
</script>
@endpush 