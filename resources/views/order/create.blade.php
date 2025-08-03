@extends('layouts.guest')

@section('title', 'Pesan Menu - Meja ' . $table->number)

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-table"></i> Meja {{ $table->number }}
        </h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('order.store', $table->id) }}" id="orderForm">
            @csrf
            
            <!-- Customer Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="customer_name" class="form-label">Nama (Opsional)</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" 
                           value="{{ old('customer_name') }}" placeholder="Masukkan nama Anda">
                </div>
                <div class="col-md-6">
                    <label for="customer_phone" class="form-label">No. Telepon (Opsional)</label>
                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" 
                           value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <!-- Menu Selection -->
            <h5 class="mb-3">
                <i class="bi bi-list-ul"></i> Pilih Menu
            </h5>
            
            <div id="menuContainer">
                @foreach($categories as $category)
                    @if($category->menus->count() > 0)
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-collection"></i> {{ $category->name }}
                            </h6>
                            
                            @foreach($category->menus as $menu)
                                <div class="menu-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex align-items-center">
                                                @if($menu->image)
                                                    <img src="{{ asset('storage/' . $menu->image) }}" 
                                                         alt="{{ $menu->name }}" 
                                                         class="rounded me-3" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $menu->name }}</h6>
                                                    <p class="text-muted mb-1 small">{{ $menu->description }}</p>
                                                    <strong class="text-primary">Rp{{ number_format($menu->price) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="quantity-control">
                                                <button type="button" class="quantity-btn" 
                                                        onclick="changeQuantity({{ $menu->id }}, -1)">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="number" 
                                                       class="quantity-input" 
                                                       id="quantity_{{ $menu->id }}"
                                                       name="items[{{ $menu->id }}][quantity]" 
                                                       value="0" 
                                                       min="0" 
                                                       max="10"
                                                       onchange="updateTotal()">
                                                <button type="button" class="quantity-btn" 
                                                        onclick="changeQuantity({{ $menu->id }}, 1)">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                                <input type="hidden" 
                                                       name="items[{{ $menu->id }}][menu_id]" 
                                                       value="{{ $menu->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="card bg-light mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-cart"></i> Ringkasan Pesanan
                    </h6>
                    <div id="orderSummary">
                        <p class="text-muted">Belum ada item yang dipilih</p>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Contoh: Pedas level 2, tidak pakai bawang">{{ old('notes') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <h5>Total: <span id="totalAmount" class="text-primary">Rp0</span></h5>
                                <small class="text-muted">*Sudah termasuk pajak 10%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                    <i class="bi bi-send"></i> Kirim Pesanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let menuPrices = {};
let selectedItems = {};

// Initialize menu prices
@foreach($categories as $category)
    @foreach($category->menus as $menu)
        menuPrices[{{ $menu->id }}] = {{ $menu->price }};
    @endforeach
@endforeach

function changeQuantity(menuId, change) {
    const input = document.getElementById('quantity_' + menuId);
    let currentValue = parseInt(input.value) || 0;
    let newValue = currentValue + change;
    
    if (newValue >= 0 && newValue <= 10) {
        input.value = newValue;
        updateTotal();
    }
}

function updateTotal() {
    let total = 0;
    let itemCount = 0;
    selectedItems = {};
    
    // Calculate total and collect selected items
    Object.keys(menuPrices).forEach(menuId => {
        const input = document.getElementById('quantity_' + menuId);
        const quantity = parseInt(input.value) || 0;
        
        if (quantity > 0) {
            const subtotal = quantity * menuPrices[menuId];
            total += subtotal;
            itemCount += quantity;
            
            selectedItems[menuId] = {
                quantity: quantity,
                price: menuPrices[menuId],
                subtotal: subtotal
            };
        }
    });
    
    // Update total display
    const totalWithTax = total + (total * 0.1);
    document.getElementById('totalAmount').textContent = 'Rp' + totalWithTax.toLocaleString('id-ID');
    
    // Update order summary
    updateOrderSummary();
    
    // Enable/disable submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = itemCount === 0;
}

function updateOrderSummary() {
    const summary = document.getElementById('orderSummary');
    
    if (Object.keys(selectedItems).length === 0) {
        summary.innerHTML = '<p class="text-muted">Belum ada item yang dipilih</p>';
        return;
    }
    
    let summaryHTML = '<div class="row">';
    Object.keys(selectedItems).forEach(menuId => {
        const item = selectedItems[menuId];
        summaryHTML += `
            <div class="col-12 mb-2">
                <div class="d-flex justify-content-between">
                    <span>${getMenuName(menuId)} x${item.quantity}</span>
                    <span>Rp${item.subtotal.toLocaleString('id-ID')}</span>
                </div>
            </div>
        `;
    });
    summaryHTML += '</div>';
    
    summary.innerHTML = summaryHTML;
}

function getMenuName(menuId) {
    // This would need to be populated with actual menu names
    // For now, we'll use a simple approach
    const menuElements = document.querySelectorAll('.menu-item');
    for (let element of menuElements) {
        const input = element.querySelector('input[name*="[menu_id]"]');
        if (input && input.value == menuId) {
            const nameElement = element.querySelector('h6');
            return nameElement ? nameElement.textContent : 'Menu ' + menuId;
        }
    }
    return 'Menu ' + menuId;
}

// Form validation
document.getElementById('orderForm').addEventListener('submit', function(e) {
    const totalItems = Object.values(selectedItems).reduce((sum, item) => sum + item.quantity, 0);
    
    if (totalItems === 0) {
        e.preventDefault();
        alert('Silakan pilih minimal satu menu');
        return false;
    }
    
    if (totalItems > 10) {
        e.preventDefault();
        alert('Maksimal 10 item per pesanan');
        return false;
    }
    
    // Hapus input yang tidak dipilih (quantity = 0) sebelum submit
    Object.keys(menuPrices).forEach(menuId => {
        const quantityInput = document.getElementById('quantity_' + menuId);
        const menuIdInput = document.querySelector(`input[name="items[${menuId}][menu_id]"]`);
        
        if (quantityInput && parseInt(quantityInput.value) === 0) {
            // Hapus input quantity dan menu_id yang tidak dipilih
            if (quantityInput) quantityInput.remove();
            if (menuIdInput) menuIdInput.remove();
        }
    });
});
</script>
@endsection 