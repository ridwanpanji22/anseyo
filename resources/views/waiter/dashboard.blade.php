@extends('layouts.admin')

@section('title', 'Dashboard Waiter - Anseyo Restaurant')

@section('page-title', 'Dashboard Waiter')
@section('page-subtitle', 'Kelola pesanan untuk waiter')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard Waiter</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
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
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['served'] }}</h4>
                        <p class="mb-0">Telah Disajikan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-truck fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['completed_today'] }}</h4>
                        <p class="mb-0">Selesai Hari Ini</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check2-all fs-1"></i>
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
                        <h4 class="mb-0">{{ $stats['occupied_tables'] }}</h4>
                        <p class="mb-0">Meja Terisi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-table fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Management -->
<div class="row">
    <!-- Ready Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Siap Disajikan ({{ $readyOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="ready-orders">
                @if($readyOrders->count() > 0)
                    @foreach($readyOrders as $order)
                        @include('waiter.partials.order-card', ['order' => $order, 'status' => 'ready'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan siap disajikan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Served Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-truck me-2"></i>
                    Telah Disajikan ({{ $servedOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="served-orders">
                @if($servedOrders->count() > 0)
                    @foreach($servedOrders as $order)
                        @include('waiter.partials.order-card', ['order' => $order, 'status' => 'served'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan telah disajikan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Completed Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-check2-all me-2"></i>
                    Selesai Hari Ini ({{ $completedOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="completed-orders">
                @if($completedOrders->count() > 0)
                    @foreach($completedOrders as $order)
                        @include('waiter.partials.order-card', ['order' => $order, 'status' => 'completed'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan selesai</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tables Status -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-table me-2"></i>
                    Status Meja
                </h5>
            </div>
            <div class="card-body" id="tables-status">
                @include('waiter.partials.tables-list', ['tables' => $tables])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh every 10 seconds
    setInterval(function() {
        updateOrders();
        updateTables();
    }, 10000);

    // Update orders function
    function updateOrders() {
        fetch('{{ route("waiter.orders.by-status") }}?status=ready')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('ready-orders').innerHTML = data.html;
                }
            });

        fetch('{{ route("waiter.orders.by-status") }}?status=served')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('served-orders').innerHTML = data.html;
                }
            });

        fetch('{{ route("waiter.orders.by-status") }}?status=completed')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('completed-orders').innerHTML = data.html;
                }
            });
    }

    // Update tables function
    function updateTables() {
        fetch('{{ route("waiter.tables.status") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('tables-status').innerHTML = data.html;
                }
            });
    }

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
                        updateOrders();
                        updateTables();
                    }, 1000);
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
                        updateOrders();
                        updateTables();
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
