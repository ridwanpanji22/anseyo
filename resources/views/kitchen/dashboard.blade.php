@extends('layouts.admin')

@section('title', 'Dashboard Dapur - Anseyo Restaurant')

@section('page-title', 'Dashboard Dapur')
@section('page-subtitle', 'Kelola pesanan untuk dapur')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard Dapur</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                        <p class="mb-0">Menunggu</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['preparing'] }}</h4>
                        <p class="mb-0">Sedang Disiapkan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-fire fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['ready'] }}</h4>
                        <p class="mb-0">Siap Disajikan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_today'] }}</h4>
                        <p class="mb-0">Total Hari Ini</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-calendar-day fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Management -->
<div class="row">
    <!-- Pending Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock me-2"></i>
                    Menunggu ({{ $pendingOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="pending-orders">
                @if($pendingOrders->count() > 0)
                    @foreach($pendingOrders as $order)
                        @include('kitchen.partials.order-card', ['order' => $order, 'status' => 'pending'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan menunggu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Preparing Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-fire me-2"></i>
                    Sedang Disiapkan ({{ $preparingOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="preparing-orders">
                @if($preparingOrders->count() > 0)
                    @foreach($preparingOrders as $order)
                        @include('kitchen.partials.order-card', ['order' => $order, 'status' => 'preparing'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan sedang disiapkan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Ready Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Siap Disajikan ({{ $readyOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="ready-orders">
                @if($readyOrders->count() > 0)
                    @foreach($readyOrders as $order)
                        @include('kitchen.partials.order-card', ['order' => $order, 'status' => 'ready'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan siap</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh every 10 seconds
    setInterval(function() {
        updateOrders();
    }, 10000);

    // Update orders function
    function updateOrders() {
        fetch('{{ route("kitchen.orders.by-status") }}?status=pending')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('pending-orders').innerHTML = data.html;
                }
            });

        fetch('{{ route("kitchen.orders.by-status") }}?status=preparing')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('preparing-orders').innerHTML = data.html;
                }
            });

        fetch('{{ route("kitchen.orders.by-status") }}?status=ready')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('ready-orders').innerHTML = data.html;
                }
            });
    }

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
                        updateOrders();
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
                        updateOrders();
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
});
</script>
@endpush
