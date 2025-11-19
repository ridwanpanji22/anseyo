<div class="order-card mb-3 border rounded p-3" data-order-id="{{ $order->id }}">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <h6 class="mb-1 fw-bold">#{{ $order->order_number }}</h6>
            <small class="text-muted">
                <i class="bi bi-table me-1"></i>
                Meja {{ $order->table->number ?? 'N/A' }}
            </small>
        </div>
        <div class="text-end">
            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
            <br>
            <span class="badge bg-{{ $paymentStatus == 'unpaid' ? 'danger' : ($paymentStatus == 'partial' ? 'warning' : 'success') }}">
                {{ ucfirst($paymentStatus) }}
            </span>
        </div>
    </div>

    @if($order->customer_name)
    <div class="mb-2">
        <small class="text-muted">
            <i class="bi bi-person me-1"></i>
            {{ $order->customer_name }}
        </small>
    </div>
    @endif

    <div class="order-items mb-3">
        @foreach($order->orderItems as $item)
        <div class="d-flex justify-content-between align-items-center mb-1">
            <div>
                <small class="fw-medium">{{ $item->menu_name }}</small>
                @if($item->notes)
                    <br><small class="text-muted">{{ $item->notes }}</small>
                @endif
            </div>
            <div class="text-end">
                <span class="badge bg-secondary">{{ $item->quantity }}x</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <small class="text-muted">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</small>
            @if($paymentStatus == 'partial')
                <br><small class="text-warning">Dibayar: Rp {{ number_format($order->amount_paid ?? 0, 0, ',', '.') }}</small>
                <br><small class="text-danger">Sisa: Rp {{ number_format($order->remaining_amount ?? $order->total, 0, ',', '.') }}</small>
            @endif
            @if($paymentStatus == 'paid')
                <br><small class="text-success">Dibayar: Rp {{ number_format($order->amount_received ?? 0, 0, ',', '.') }}</small>
                @if($order->change_amount > 0)
                    <br><small class="text-info">Kembalian: Rp {{ number_format($order->change_amount, 0, ',', '.') }}</small>
                @endif
                <br><small class="text-muted">Metode: {{ strtoupper($order->payment_method ?? 'cash') }}</small>
            @endif
        </div>
        <div class="btn-group btn-group-sm">
            @if($paymentStatus == 'unpaid')
                <button type="button" 
                        class="btn btn-warning btn-sm" 
                        onclick="showPartialPayment({{ $order->id }}, {{ $order->total }})"
                        title="Bayar Sebagian">
                    <i class="bi bi-clock"></i>
                </button>
                <button type="button" 
                        class="btn btn-success btn-sm" 
                        onclick="showFullPayment({{ $order->id }}, {{ $order->total }})"
                        title="Bayar Lunas">
                    <i class="bi bi-check-lg"></i>
                </button>
            @elseif($paymentStatus == 'partial')
                <button type="button" 
                        class="btn btn-success btn-sm" 
                        onclick="showFullPayment({{ $order->id }}, {{ $order->remaining_amount ?? $order->total }})"
                        title="Bayar Sisa">
                    <i class="bi bi-check-lg"></i>
                </button>
            @endif
            @if($paymentStatus == 'paid' && $order->receipt_number)
                <button type="button" 
                        class="btn btn-info btn-sm" 
                        onclick="printReceipt({{ $order->id }})"
                        title="Cetak Struk">
                    <i class="bi bi-printer"></i>
                </button>
            @endif
            <a href="{{ route('cashier.orders.show', $order->id) }}" 
               class="btn btn-outline-secondary btn-sm"
               title="Lihat Detail">
                <i class="bi bi-eye"></i>
            </a>
        </div>
    </div>

    @if($order->notes)
    <div class="mt-2">
        <small class="text-muted">
            <i class="bi bi-chat-text me-1"></i>
            {{ $order->notes }}
        </small>
    </div>
    @endif
</div> 