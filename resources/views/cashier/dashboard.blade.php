@extends('layouts.admin')

@section('title', 'Dashboard Cashier - Anseyo Restaurant')

@section('page-title', 'Dashboard Cashier')
@section('page-subtitle', 'Kelola pembayaran untuk cashier')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Dashboard Cashier</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['unpaid'] }}</h4>
                        <p class="mb-0">Belum Bayar</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-x-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['partial'] }}</h4>
                        <p class="mb-0">Bayar Sebagian</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock fs-1"></i>
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
                        <h4 class="mb-0">{{ $stats['paid_today'] }}</h4>
                        <p class="mb-0">Lunas Hari Ini</p>
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
                        <h4 class="mb-0">Rp {{ number_format($stats['total_revenue_today'], 0, ',', '.') }}</h4>
                        <p class="mb-0">Pendapatan Hari Ini</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-cash-coin fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Management -->
<div class="row">
    <!-- Unpaid Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-x-circle me-2"></i>
                    Belum Bayar ({{ $unpaidOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="unpaid-orders">
                @if($unpaidOrders->count() > 0)
                    @foreach($unpaidOrders as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'paymentStatus' => 'unpaid'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan belum bayar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Partial Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock me-2"></i>
                    Bayar Sebagian ({{ $partialOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="partial-orders">
                @if($partialOrders->count() > 0)
                    @foreach($partialOrders as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'paymentStatus' => 'partial'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan bayar sebagian</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Paid Orders -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-check-circle me-2"></i>
                    Lunas Hari Ini ({{ $paidOrders->count() }})
                </h5>
            </div>
            <div class="card-body" id="paid-orders">
                @if($paidOrders->count() > 0)
                    @foreach($paidOrders as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'paymentStatus' => 'paid'])
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <p class="mt-2 text-muted">Tidak ada pesanan lunas hari ini</p>
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
                @include('cashier.partials.tables-list', ['tables' => $tables])
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pembayaran Sebagian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="partialPaymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="amount_paid" class="form-label">Jumlah yang Dibayar</label>
                        <input type="number" class="form-control" id="amount_paid" name="amount_paid" required>
                        <div class="form-text">Total pesanan: <span id="order_total">Rp 0</span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                </div>
            </form>
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
        updateTables();
    }, 10000);

    // Update orders function
    function updateOrders() {
        fetch('{{ route("cashier.orders.by-payment-status") }}?payment_status=unpaid')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('unpaid-orders').innerHTML = data.html;
                }
            });

        fetch('{{ route("cashier.orders.by-payment-status") }}?payment_status=partial')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('partial-orders').innerHTML = data.html;
                }
            });

        fetch('{{ route("cashier.orders.by-payment-status") }}?payment_status=paid')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('paid-orders').innerHTML = data.html;
                }
            });
    }

    // Update tables function
    function updateTables() {
        fetch('{{ route("cashier.tables.status") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('tables-status').innerHTML = data.html;
                }
            });
    }

    // Mark order as paid
    window.markPaid = function(orderId) {
        if (confirm('Tandai pesanan ini sebagai lunas?')) {
            fetch(`/cashier/orders/${orderId}/mark-paid`, {
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

    // Show partial payment modal
    window.showPartialPayment = function(orderId, orderTotal) {
        document.getElementById('order_total').textContent = 'Rp ' + orderTotal.toLocaleString('id-ID');
        document.getElementById('amount_paid').max = orderTotal;
        document.getElementById('amount_paid').value = '';
        
        const form = document.getElementById('partialPaymentForm');
        form.action = `/cashier/orders/${orderId}/mark-partial-payment`;
        
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();
    };

    // Handle partial payment form submission
    document.getElementById('partialPaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const orderId = this.action.split('/').pop();
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                amount_paid: formData.get('amount_paid')
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                setTimeout(() => {
                    updateOrders();
                    updateTables();
                }, 1000);
            }
        });
    });

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
