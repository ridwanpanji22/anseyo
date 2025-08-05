@if($orders->count() > 0)
    @foreach($orders as $order)
        @include('kitchen.partials.order-card', ['order' => $order, 'status' => $status])
    @endforeach
@else
    <div class="text-center py-4">
        <i class="bi bi-check-circle fs-1 text-success"></i>
        <p class="mt-2 text-muted">
            @if($status == 'pending')
                Tidak ada pesanan menunggu
            @elseif($status == 'preparing')
                Tidak ada pesanan sedang disiapkan
            @elseif($status == 'ready')
                Tidak ada pesanan siap
            @else
                Tidak ada pesanan
            @endif
        </p>
    </div>
@endif 