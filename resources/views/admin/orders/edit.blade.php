@extends('layouts.admin')

@section('title', 'Edit Pesanan - Anseyo Restaurant')

@section('page-title', 'Edit Pesanan')
@section('page-subtitle', 'Ubah informasi pesanan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Manajemen Pesanan</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.show', $order->id) }}">Detail Pesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Pesanan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Edit Pesanan #{{ $order->order_number }}</h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
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

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order_number" class="form-label">Nomor Pesanan</label>
                                <input type="text" class="form-control" id="order_number" value="{{ $order->order_number }}" readonly>
                                <div class="form-text">Nomor pesanan tidak dapat diubah</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="table_id" class="form-label">Meja</label>
                                <select class="form-select" id="table_id" name="table_id" required>
                                    @foreach(\App\Models\Table::all() as $table)
                                        <option value="{{ $table->id }}" {{ $order->table_id == $table->id ? 'selected' : '' }}>
                                            {{ $table->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Status Pembayaran</label>
                                <select class="form-select" id="payment_status" name="payment_status" required>
                                    <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                                    <option value="partial" {{ $order->payment_status == 'partial' ? 'selected' : '' }}>Bayar Sebagian</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Admin</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Catatan tambahan...">{{ $order->notes }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Order Summary -->
        <div class="card">
            <div class="card-header">
                <h5>Ringkasan Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Total Items:</strong> {{ $order->orderItems->count() }}
                </div>
                <div class="mb-3">
                    <strong>Total Amount:</strong> 
                    <span class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
                <div class="mb-3">
                    <strong>Waktu Pesanan:</strong><br>
                    <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                </div>
                @if($order->updated_at != $order->created_at)
                <div class="mb-3">
                    <strong>Terakhir Diupdate:</strong><br>
                    <small>{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items Preview -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>Item Pesanan</h5>
            </div>
            <div class="card-body">
                @foreach($order->orderItems as $item)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>{{ $item->menu->name }}</strong>
                        <br><small class="text-muted">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                    </div>
                    <div class="text-end">
                        <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                    </div>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <strong>Total:</strong>
                    <strong class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-save draft functionality
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    // Save form data to localStorage when form changes
    form.addEventListener('change', function() {
        const formData = new FormData(form);
        const data = {};
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        localStorage.setItem('orderEditDraft', JSON.stringify(data));
    });
    
    // Load draft data if exists
    const draft = localStorage.getItem('orderEditDraft');
    if (draft) {
        const data = JSON.parse(draft);
        for (let key in data) {
            const element = form.querySelector(`[name="${key}"]`);
            if (element) {
                element.value = data[key];
            }
        }
    }
    
    // Clear draft when form is submitted
    form.addEventListener('submit', function() {
        localStorage.removeItem('orderEditDraft');
    });
});
</script>
@endpush 