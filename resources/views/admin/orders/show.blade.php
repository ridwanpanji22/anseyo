@extends('layouts.admin')

@section('title', 'Detail Pesanan - Anseyo Restaurant')

@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Informasi lengkap pesanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Manajemen Pesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Pesanan #{{ $order->order_number }}</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Order Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Pesanan</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td width="120">No. Pesanan:</td>
                                <td><strong>#{{ $order->order_number }}</strong></td>
                            </tr>
                            <tr>
                                <td>Meja:</td>
                                <td><span class="badge bg-primary">{{ $order->table->name ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <td>Waktu Pesanan:</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'preparing' => 'info',
                                            'ready' => 'primary',
                                            'served' => 'success',
                                            'completed' => 'secondary',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pending',
                                            'preparing' => 'Menyiapkan',
                                            'ready' => 'Siap',
                                            'served' => 'Disajikan',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] }}">
                                        {{ $statusLabels[$order->status] }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Pembayaran:</td>
                                <td>
                                    @php
                                        $paymentColors = [
                                            'unpaid' => 'danger',
                                            'partial' => 'warning',
                                            'paid' => 'success'
                                        ];
                                        $paymentLabels = [
                                            'unpaid' => 'Belum Bayar',
                                            'partial' => 'Bayar Sebagian',
                                            'paid' => 'Lunas'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $paymentColors[$order->payment_status] }}">
                                        {{ $paymentLabels[$order->payment_status] }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Timeline</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Pesanan Dibuat</h6>
                                    <p class="timeline-text">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @if($order->prepared_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Makanan Disiapkan</h6>
                                    <p class="timeline-text">{{ $order->prepared_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            @if($order->served_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Makanan Disajikan</h6>
                                    <p class="timeline-text">{{ $order->served_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                            @if($order->completed_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Pesanan Selesai</h6>
                                    <p class="timeline-text">{{ $order->completed_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <h6 class="text-muted mb-3">Item Pesanan</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->menu->image)
                                            <img src="{{ asset('storage/' . $item->menu->image) }}" 
                                                 alt="{{ $item->menu->name }}" 
                                                 class="rounded me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $item->menu->name }}</strong>
                                            @if($item->notes)
                                                <br><small class="text-muted">{{ $item->notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td><strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($order->notes)
                <div class="mt-3">
                    <h6 class="text-muted">Catatan Pesanan</h6>
                    <div class="alert alert-info">
                        {{ $order->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Update Card -->
        <div class="card">
            <div class="card-header">
                <h5>Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Pesanan</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Menyiapkan</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Siap</option>
                            <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Disajikan</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                            <option value="partial" {{ $order->payment_status == 'partial' ? 'selected' : '' }}>Bayar Sebagian</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan tambahan...">{{ $order->notes }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-1"></i>Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($order->status == 'pending')
                        <button class="btn btn-info btn-sm" onclick="updateStatus('preparing')">
                            <i class="bi bi-play-circle me-1"></i>Mulai Menyiapkan
                        </button>
                    @endif
                    
                    @if($order->status == 'preparing')
                        <button class="btn btn-primary btn-sm" onclick="updateStatus('ready')">
                            <i class="bi bi-check-circle me-1"></i>Makanan Siap
                        </button>
                    @endif
                    
                    @if($order->status == 'ready')
                        <button class="btn btn-success btn-sm" onclick="updateStatus('served')">
                            <i class="bi bi-arrow-right-circle me-1"></i>Makanan Disajikan
                        </button>
                    @endif
                    
                    @if($order->status == 'served')
                        <button class="btn btn-secondary btn-sm" onclick="updateStatus('completed')">
                            <i class="bi bi-check2-all me-1"></i>Selesaikan Pesanan
                        </button>
                    @endif
                    
                    @if($order->payment_status != 'paid')
                        <button class="btn btn-warning btn-sm" onclick="updatePaymentStatus('paid')">
                            <i class="bi bi-credit-card me-1"></i>Set Lunas
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
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
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content {
    padding-left: 10px;
}

.timeline-title {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}
</style>
@endpush

@push('js')
<script>
function updateStatus(status) {
    if (confirm('Apakah Anda yakin ingin mengubah status pesanan?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.orders.update", $order->id) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        const paymentStatus = document.createElement('input');
        paymentStatus.type = 'hidden';
        paymentStatus.name = 'payment_status';
        paymentStatus.value = '{{ $order->payment_status }}';
        
        form.appendChild(csrfToken);
        form.appendChild(method);
        form.appendChild(statusInput);
        form.appendChild(paymentStatus);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function updatePaymentStatus(status) {
    if (confirm('Apakah Anda yakin ingin mengubah status pembayaran?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.orders.update", $order->id) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'PUT';
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = '{{ $order->status }}';
        
        const paymentStatus = document.createElement('input');
        paymentStatus.type = 'hidden';
        paymentStatus.name = 'payment_status';
        paymentStatus.value = status;
        
        form.appendChild(csrfToken);
        form.appendChild(method);
        form.appendChild(statusInput);
        form.appendChild(paymentStatus);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush 