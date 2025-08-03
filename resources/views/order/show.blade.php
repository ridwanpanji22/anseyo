@extends('layouts.guest')

@section('title', 'Status Pesanan - ' . $order->order_number)

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="bi bi-receipt"></i> Status Pesanan
            </h4>
            <span class="badge bg-light text-dark fs-6">{{ $order->order_number }}</span>
        </div>
    </div>
    <div class="card-body">
        <!-- Order Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-primary">
                    <i class="bi bi-info-circle"></i> Informasi Pesanan
                </h6>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Meja:</strong></td>
                        <td>{{ $order->table->number }}</td>
                    </tr>
                    @if($order->customer_name)
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $order->customer_name }}</td>
                    </tr>
                    @endif
                    @if($order->customer_phone)
                    <tr>
                        <td><strong>Telepon:</strong></td>
                        <td>{{ $order->customer_phone }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td><strong>Tanggal:</strong></td>
                        <td>{{ $order->ordered_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="text-primary">
                    <i class="bi bi-clock"></i> Status Pesanan
                </h6>
                <div class="text-center">
                    @php
                        $statusClass = '';
                        $statusText = '';
                        switch($order->status) {
                            case 'pending':
                                $statusClass = 'status-pending';
                                $statusText = 'Menunggu';
                                break;
                            case 'preparing':
                                $statusClass = 'status-preparing';
                                $statusText = 'Sedang Disiapkan';
                                break;
                            case 'ready':
                                $statusClass = 'status-ready';
                                $statusText = 'Siap Diantar';
                                break;
                            case 'completed':
                                $statusClass = 'status-completed';
                                $statusText = 'Selesai';
                                break;
                            default:
                                $statusClass = 'status-pending';
                                $statusText = ucfirst($order->status);
                        }
                    @endphp
                    <div class="status-badge {{ $statusClass }} mb-2">
                        {{ $statusText }}
                    </div>
                    <small class="text-muted">
                        @if($order->status == 'pending')
                            Pesanan Anda sedang diproses oleh dapur
                        @elseif($order->status == 'preparing')
                            Makanan sedang disiapkan oleh chef
                        @elseif($order->status == 'ready')
                            Makanan siap diantar oleh waiter
                        @elseif($order->status == 'completed')
                            Pesanan telah selesai
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-4">
            <h6 class="text-primary">
                <i class="bi bi-list-ul"></i> Detail Pesanan
            </h6>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
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
                                    @if($item->menu && $item->menu->image)
                                        <img src="{{ asset('storage/' . $item->menu->image) }}" 
                                             alt="{{ $item->menu_name }}" 
                                             class="rounded me-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $item->menu_name }}</strong>
                                        @if($item->notes)
                                            <br><small class="text-muted">{{ $item->notes }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">Rp{{ number_format($item->price) }}</td>
                            <td class="text-end">Rp{{ number_format($item->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-calculator"></i> Ringkasan Pembayaran
                </h6>
                <div class="row">
                    <div class="col-md-8">
                        @if($order->notes)
                        <div class="mb-3">
                            <strong>Catatan:</strong>
                            <p class="mb-0 text-muted">{{ $order->notes }}</p>
                        </div>
                        @endif
                        
                        <!-- Payment Status -->
                        <div class="mb-3">
                            <strong>Status Pembayaran:</strong>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success ms-2">Lunas</span>
                            @else
                                <span class="badge bg-warning ms-2">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr>
                                <td>Subtotal:</td>
                                <td class="text-end">Rp{{ number_format($order->subtotal) }}</td>
                            </tr>
                            <tr>
                                <td>Pajak (10%):</td>
                                <td class="text-end">Rp{{ number_format($order->tax) }}</td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Total:</strong></td>
                                <td class="text-end"><strong>Rp{{ number_format($order->total) }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <button class="btn btn-outline-primary me-2" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i> Refresh Status
            </button>
            <a href="{{ route('order.create', $order->table->id) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Pesan Lagi
            </a>
        </div>
    </div>
</div>

<!-- Auto refresh every 30 seconds -->
<script>
setInterval(function() {
    location.reload();
}, 30000); // 30 seconds
</script>
@endsection 