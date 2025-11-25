@extends('layouts.admin')

@section('title', 'Dashboard Cashier - Anseyo Restaurant')

@section('page-title', 'Dashboard Cashier')
@section('page-subtitle', 'Kelola pembayaran untuk cashier')

@section('breadcrumb')
    <li class='breadcrumb-item active' aria-current='page'>Dashboard Cashier</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class='row mb-4'>
    <div class='col-md-3'>
        <div class='card bg-danger text-white'>
            <div class='card-body'>
                <div class='d-flex justify-content-between'>
                    <div>
                        <h4 class='mb-0'>{{ $stats['unpaid'] }}</h4>
                        <p class='mb-0'>Belum Bayar</p>
                    </div>
                    <div class='align-self-center'>
                        <i class='bi bi-x-circle fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-3'>
        <div class='card bg-warning text-white'>
            <div class='card-body'>
                <div class='d-flex justify-content-between'>
                    <div>
                        <h4 class='mb-0'>{{ $stats['occupied_tables'] }}</h4>
                        <p class='mb-0'>Meja Terpakai</p>
                    </div>
                    <div class='align-self-center'>
                        <i class='bi bi-people-fill fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-3'>
        <div class='card bg-success text-white'>
            <div class='card-body'>
                <div class='d-flex justify-content-between'>
                    <div>
                        <h4 class='mb-0'>{{ $stats['paid_today'] }}</h4>
                        <p class='mb-0'>Lunas Hari Ini</p>
                    </div>
                    <div class='align-self-center'>
                        <i class='bi bi-check-circle fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='col-md-3'>
        <div class='card bg-primary text-white'>
            <div class='card-body'>
                <div class='d-flex justify-content-between'>
                    <div>
                        <h4 class='mb-0'>Rp {{ number_format($stats['total_revenue_today'], 0, ',', '.') }}</h4>
                        <p class='mb-0'>Pendapatan Hari Ini</p>
                    </div>
                    <div class='align-self-center'>
                        <i class='bi bi-cash-coin fs-1'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Management -->
<div class='row'>
    <!-- Unpaid Orders -->
    <div class='col-lg-6'>
        <div class='card'>
            <div class='card-header bg-danger text-white'>
                <h5 class='mb-0'>
                    <i class='bi bi-x-circle me-2'></i>
                    Belum Bayar ({{ $unpaidOrders->count() }})
                </h5>
            </div>
            <div class='card-body' id='unpaid-orders'>
                @if($unpaidOrders->count() > 0)
                    @foreach($unpaidOrders as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'paymentStatus' => 'unpaid'])
                    @endforeach
                @else
                    <div class='text-center py-4'>
                        <i class='bi bi-check-circle fs-1 text-success'></i>
                        <p class='mt-2 text-muted'>Tidak ada pesanan belum bayar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Paid Orders -->
    <div class='col-lg-6'>
        <div class='card'>
            <div class='card-header bg-success text-white'>
                <h5 class='mb-0'>
                    <i class='bi bi-check-circle me-2'></i>
                    Lunas Hari Ini ({{ $paidOrders->count() }})
                </h5>
            </div>
            <div class='card-body' id='paid-orders'>
                @if($paidOrders->count() > 0)
                    @foreach($paidOrders as $order)
                        @include('cashier.partials.order-card', ['order' => $order, 'paymentStatus' => 'paid'])
                    @endforeach
                @else
                    <div class='text-center py-4'>
                        <i class='bi bi-check-circle fs-1 text-success'></i>
                        <p class='mt-2 text-muted'>Tidak ada pesanan lunas hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tables Status -->
<div class='row mt-4'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-header bg-info text-white'>
                <h5 class='mb-0'>
                    <i class='bi bi-table me-2'></i>
                    Status Meja
                </h5>
            </div>
            <div class='card-body' id='tables-status'>
                @include('cashier.partials.tables-list', ['tables' => $tables])
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class='modal fade' id='paymentModal' tabindex='-1' aria-labelledby='paymentModalLabel' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='paymentModalLabel'>Pembayaran</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <form id='paymentForm' method='POST'>
                @csrf
                <div class='modal-body'>
                    <input type='hidden' id='order_total_value' value='0'>
                    <div class='mb-3'>
                        <label class='form-label'>Total Tagihan</label>
                        <input type='text' class='form-control' id='order_total_display' value='Rp 0' readonly>
                    </div>
                    <div class='mb-3'>
                        <label for='amount_received' class='form-label'>Uang yang Diterima</label>
                        <input type='number' class='form-control' id='amount_received' name='amount_received' required>
                        <div class='form-text'>Kembalian: <span id='change_amount'>Rp 0</span></div>
                    </div>
                    <div class='mb-3'>
                        <label for='payment_method' class='form-label'>Metode Pembayaran</label>
                        <select class='form-select' id='payment_method' name='payment_method' required>
                            <option value='cash'>Cash</option>
                            <option value='card'>Card</option>
                            <option value='qris'>QRIS</option>
                            <option value='transfer'>Transfer</option>
                        </select>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Batal</button>
                    <button type='submit' class='btn btn-primary'>Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModalEl = document.getElementById('paymentModal');
    const paymentModal = new bootstrap.Modal(paymentModalEl);
    const paymentForm = document.getElementById('paymentForm');
    const orderTotalInput = document.getElementById('order_total_value');
    const orderTotalDisplay = document.getElementById('order_total_display');
    const amountReceivedInput = document.getElementById('amount_received');
    const paymentMethodInput = document.getElementById('payment_method');
    const changeAmountLabel = document.getElementById('change_amount');

    updateOrders();
    updateTables();

    setInterval(function() {
        updateOrders();
        updateTables();
    }, 10000);

    function updateOrders() {
        fetch('{{ route("cashier.orders.by-payment-status") }}?payment_status=unpaid')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('unpaid-orders').innerHTML = data.html;
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

    function updateTables() {
        fetch('{{ route("cashier.tables.status") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('tables-status').innerHTML = data.html;
                }
            });
    }

    window.showPaymentModal = function(orderId, orderTotal) {
        orderTotalInput.value = orderTotal;
        orderTotalDisplay.value = 'Rp ' + Number(orderTotal).toLocaleString('id-ID');
        amountReceivedInput.value = orderTotal;
        changeAmountLabel.textContent = 'Rp 0';
        paymentForm.action = `/cashier/orders/${orderId}/mark-paid`;
        paymentModal.show();
    };

    amountReceivedInput.addEventListener('input', function() {
        const orderTotal = parseFloat(orderTotalInput.value) || 0;
        const amountReceived = parseFloat(this.value) || 0;
        const changeAmount = amountReceived - orderTotal;
        changeAmountLabel.textContent = 'Rp ' + changeAmount.toLocaleString('id-ID');
    });

    paymentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                amount_received: amountReceivedInput.value,
                payment_method: paymentMethodInput.value
            })
        })
        .then(async response => {
            const data = await response.json();
            return { ok: response.ok, data };
        })
        .then(({ ok, data }) => {
            if (ok && data.success) {
                showNotification(data.message, 'success');
                paymentModal.hide();
                setTimeout(() => {
                    updateOrders();
                    updateTables();
                }, 1000);
            } else {
                showNotification(data.message || 'Pembayaran gagal disimpan.', 'danger');
            }
        })
        .catch(() => {
            showNotification('Terjadi kesalahan saat menyimpan pembayaran.', 'danger');
        });
    });

    window.printReceipt = function(orderId) {
        window.open(`/cashier/orders/${orderId}/receipt`, '_blank');
    };

    function showNotification(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
        `;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
</script>
@endpush
