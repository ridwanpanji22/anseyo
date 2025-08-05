@if($orders->count() > 0)
    @foreach($orders as $order)
        @include('waiter.partials.order-card', ['order' => $order, 'status' => $status])
    @endforeach
@else
    <div class="text-center py-4">
        <i class="bi bi-check-circle fs-1 text-success"></i>
        <p class="mt-2 text-muted">
            @if($status == 'ready')
                Tidak ada pesanan siap disajikan
            @elseif($status == 'served')
                Tidak ada pesanan telah disajikan
            @elseif($status == 'completed')
                Tidak ada pesanan selesai
            @else
                Tidak ada pesanan
            @endif
        </p>
    </div>
@endif 