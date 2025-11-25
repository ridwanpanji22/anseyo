@extends('layouts.admin')

@section('title', 'Detail Pesanan - Cashier Dashboard')

@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Informasi lengkap pesanan dan pembayaran')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('cashier.dashboard') }}">Dashboard Cashier</a></li>
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
                        <strong>Status Pesanan:</strong>
                        <span class="badge bg-{{ $order->status == 'served' ? 'success' : ($order->status == 'completed' ? 'secondary' : 'warning') }}">
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
        <!-- Payment Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Informasi Pembayaran</h6>
            </div>
            <div class="card-body">
                @php
                    $paymentBadgeClasses = [
                        'unpaid' => 'danger',
                        'paid' => 'success',
                        'cancelled' => 'secondary',
                    ];
                    $paymentBadge = $paymentBadgeClasses[$order->payment_status] ?? 'secondary';
                @endphp
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Status Pembayaran:</strong>
                        <span class="badge bg-{{ $paymentBadge }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Total Tagihan:</strong>
                        <span class="fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->payment_status == 'paid')
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Waktu Pembayaran:</strong>
                        <span>{{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Uang Diterima:</strong>
                        <span class="text-success">Rp {{ number_format($order->amount_received ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Kembalian:</strong>
                        <span class="text-info">Rp {{ number_format($order->change_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Metode Pembayaran:</strong>
                        <span class="badge bg-primary">{{ ucfirst($order->payment_method ?? 'cash') }}</span>
                    </div>
                </div>

                @if($order->receipt_number)
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>No. Struk:</strong>
                        <span class="text-muted">{{ $order->receipt_number }}</span>
                    </div>
                </div>
                @endif
                @endif

                <!-- Action Buttons -->
                <div class="mt-4">
                    @if($order->payment_status == 'unpaid')
                        <button type="button" 
                                class="btn btn-success w-100 mb-2" 
                                onclick="showPaymentModal({{ $order->id }}, {{ $order->total }})">
                            <i class="bi bi-cash-stack me-2"></i>
                            Bayar Lunas
                        </button>
                    @endif

                    @if($order->payment_status == 'paid' && $order->receipt_number)
                        <button type="button" 
                                class="btn btn-info w-100 mb-2" 
                                onclick="printReceipt({{ $order->id }})">
                            <i class="bi bi-printer me-2"></i>
                            Cetak Struk
                        </button>
                    @endif

                    <a href="{{ route('cashier.dashboard') }}" 
                       class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Timeline -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Timeline Pembayaran</h6>
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

                    @if($order->served_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pesanan Disajikan</h6>
                            <small class="text-muted">{{ $order->served_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    @endif

                    @if($order->payment_status == 'paid')
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Pembayaran Lunas</h6>
                            <small class="text-muted">{{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : '-' }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="paymentForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="order_total_value" value="0">
                    <div class="mb-3">
                        <label class="form-label">Total Tagihan</label>
                        <input type="text" class="form-control" id="order_total_display" value="Rp 0" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amount_received" class="form-label">Uang yang Diterima</label>
                        <input type="number" class="form-control" id="amount_received" name="amount_received" required>
                        <div class="form-text">Kembalian: <span id="change_amount">Rp 0</span></div>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="qris">QRIS</option>
                            <option value="transfer">Transfer</option>
                        </select>
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

    window.showPaymentModal = function(orderId, orderTotal) {
        orderTotalInput.value = orderTotal;
        orderTotalDisplay.value = 'Rp ' + Number(orderTotal).toLocaleString('id-ID');
        amountReceivedInput.value = orderTotal;
        document.getElementById('change_amount').textContent = 'Rp 0';
        paymentForm.action = `/cashier/orders/${orderId}/mark-paid`;
        paymentModal.show();
    };

    amountReceivedInput.addEventListener('input', function() {
        const orderTotal = parseFloat(orderTotalInput.value) || 0;
        const amountReceived = parseFloat(this.value) || 0;
        const changeAmount = amountReceived - orderTotal;

        document.getElementById('change_amount').textContent = 'Rp ' + changeAmount.toLocaleString('id-ID');
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
                    window.location.href = '{{ route("cashier.dashboard") }}';
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
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});
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