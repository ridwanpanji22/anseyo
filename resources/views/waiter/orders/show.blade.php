@extends('layouts.admin')

@section('title', 'Detail Pesanan - Waiter Dashboard')

@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Informasi lengkap pesanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('waiter.dashboard') }}">Dashboard Waiter</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Order Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Pesanan #{{ $order->order_number }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Meja:</strong>
                        <span class="badge bg-primary">{{ $order->table->number ?? 'N/A' }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $order->status == 'ready' ? 'primary' : ($order->status == 'served' ? 'success' : 'secondary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                @if($order->customer_name)
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Pemesan:</strong>
                        <span>{{ $order->customer_name }}</span>
                    </div>
                    @if($order->customer_phone)
                    <div class="col-md-6">
                        <strong>Telepon:</strong>
                        <span>{{ $order->customer_phone }}</span>
                    </div>
                    @endif
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Waktu Pesanan:</strong>
                        <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="col-md-6">
                        <strong>Total:</strong>
                        <span class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->notes)
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Catatan:</strong>
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Order Items -->
                <div class="mt-4">
                    <h6 class="mb-3">Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="bi bi-utensils text-muted"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $item->menu_name }}</strong>
                                                @if($item->notes)
                                                    <br><small class="text-muted">{{ $item->notes }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $item->quantity }}x</span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Action Buttons -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Aksi</h6>
            </div>
            <div class="card-body">
                @if($order->status == 'ready')
                    <button type="button" 
                            class="btn btn-success w-100 mb-2" 
                            onclick="markServed({{ $order->id }})">
                        <i class="bi bi-truck me-2"></i>
                        Tandai Disajikan
                    </button>
                @elseif($order->status == 'served')
                    <button type="button" 
                            class="btn btn-secondary w-100 mb-2" 
                            onclick="markCompleted({{ $order->id }})">
                        <i class="bi bi-check-lg me-2"></i>
                        Tandai Selesai
                    </button>
                @endif

                <a href="{{ route('waiter.dashboard') }}" 
                   class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Timeline Pesanan</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pesanan Dibuat</h6>
                            <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>

                    @if($order->preparing_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Mulai Menyiapkan</h6>
                            <small class="text-muted">{{ $order->preparing_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endif

                    @if($order->ready_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Siap Disajikan</h6>
                            <small class="text-muted">{{ $order->ready_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endif

                    @if($order->served_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Telah Disajikan</h6>
                            <small class="text-muted">{{ $order->served_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endif

                    @if($order->completed_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-secondary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Selesai</h6>
                            <small class="text-muted">{{ $order->completed_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Mark order as served
window.markServed = function(orderId) {
    if (confirm('Tandai pesanan ini sebagai telah disajikan?')) {
        fetch(`/waiter/orders/${orderId}/mark-served`, {
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
                    window.location.href = '{{ route("waiter.dashboard") }}';
                }, 1500);
            }
        });
    }
};

// Mark order as completed
window.markCompleted = function(orderId) {
    if (confirm('Tandai pesanan ini sebagai selesai?')) {
        fetch(`/waiter/orders/${orderId}/mark-completed`, {
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
                    window.location.href = '{{ route("waiter.dashboard") }}';
                }, 1500);
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

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 3px);
    background-color: #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.timeline-content small {
    font-size: 0.8rem;
}
</style>
@endpush 