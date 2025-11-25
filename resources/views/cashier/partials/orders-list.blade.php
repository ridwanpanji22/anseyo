@if($orders->count() > 0)
    @foreach($orders as $order)
        @include('cashier.partials.order-card', ['order' => $order, 'paymentStatus' => $paymentStatus])
    @endforeach
@else
    <div class="text-center py-4">
        <i class="bi bi-check-circle fs-1 text-success"></i>
        <p class="mt-2 text-muted">
            @if($paymentStatus == 'unpaid')
                Tidak ada pesanan belum bayar
            @elseif($paymentStatus == 'paid')
                Tidak ada pesanan lunas hari ini
            @else
                Tidak ada pesanan
            @endif
        </p>
    </div>
@endif 