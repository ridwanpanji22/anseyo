@extends('layouts.admin')

@section('title', 'Detail Pesanan - Kitchen Dashboard')

@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Informasi lengkap pesanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('kitchen.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pesanan #{{ $order->order_number }}</h4>
            </div>
            <div class="card-body">
                <!-- Order Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informasi Pesanan</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nomor Pesanan:</strong></td>
                                <td>#{{ $order->order_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'preparing' ? 'info' : ($order->status == 'ready' ? 'success' : 'secondary')) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @if($order->customer_name)
                            <tr>
                                <td><strong>Pelanggan:</strong></td>
                                <td>{{ $order->customer_name }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Meja</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nomor Meja:</strong></td>
                                <td>{{ $order->table->number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status Meja:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $order->table->status == 'available' ? 'success' : 'danger' }}">
                                        {{ ucfirst($order->table->status ?? 'N/A') }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Item Pesanan</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $item->menu_name }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        <td>{{ $item->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>Rp {{ number_format($order->total, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6>Catatan Pesanan</h6>
                        <div class="alert alert-info">
                            {{ $order->notes }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            @if($order->status == 'pending')
                                <button type="button" 
                                        class="btn btn-info" 
                                        onclick="startPreparing({{ $order->id }})">
                                    <i class="bi bi-play-fill me-1"></i>
                                    Mulai Menyiapkan
                                </button>
                            @elseif($order->status == 'preparing')
                                <button type="button" 
                                        class="btn btn-success" 
                                        onclick="markReady({{ $order->id }})">
                                    <i class="bi bi-check-lg me-1"></i>
                                    Tandai Siap
                                </button>
                            @endif
                            
                            <a href="{{ route('kitchen.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali ke Dashboard
                            </a>
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
    // Start preparing order
    window.startPreparing = function(orderId) {
        if (confirm('Mulai menyiapkan pesanan ini?')) {
            fetch(`/kitchen/orders/${orderId}/start-preparing`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            });
        }
    };

    // Mark order as ready
    window.markReady = function(orderId) {
        if (confirm('Tandai pesanan ini sebagai siap?')) {
            fetch(`/kitchen/orders/${orderId}/mark-ready`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            });
        }
    };

    // Show notification
    function showNotification(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
</script>
@endpush 