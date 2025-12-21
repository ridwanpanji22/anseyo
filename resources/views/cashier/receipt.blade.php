<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->receipt_number }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
            background: white;
        }
        
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            text-align: center;
        }
        
        .header {
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .restaurant-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .restaurant-address {
            font-size: 10px;
            margin-bottom: 5px;
        }
        
        .receipt-info {
            text-align: left;
            margin-bottom: 10px;
        }
        
        .items {
            text-align: left;
            margin-bottom: 10px;
        }
        
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .item-name {
            flex: 1;
        }
        
        .item-qty {
            margin: 0 5px;
        }
        
        .item-price {
            text-align: right;
            min-width: 60px;
        }
        
        .totals {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            text-align: left;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .payment-info {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            text-align: left;
        }
        
        .footer {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
        }
        
        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .print-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">Cetak Struk</button>
    
    <div class="receipt">
        <div class="header">
            <div class="restaurant-name">ANSEYO RESTAURANT</div>
            <div class="restaurant-address">Jl. Contoh No. 123, Jakarta</div>
            <div class="restaurant-address">Telp: (021) 1234-5678</div>
        </div>
        
        <div class="receipt-info">
            <div>No. Struk: {{ $order->receipt_number }}</div>
            <div>No. Pesanan: {{ $order->order_number }}</div>
            <div>Tanggal: {{ $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : $order->created_at->format('d/m/Y H:i') }}</div>
            <div>Meja: {{ $order->table->number ?? 'N/A' }}</div>
            @if($order->customer_name)
            <div>Pemesan: {{ $order->customer_name }}</div>
            @endif
        </div>
        
        <div class="items">
            <div style="border-bottom: 1px solid #000; margin-bottom: 5px; padding-bottom: 3px;">
                <div style="display: flex; justify-content: space-between; font-weight: bold;">
                    <span>Item</span>
                    <span>Qty</span>
                    <span>Harga</span>
                </div>
            </div>
            
            @foreach($order->orderItems as $item)
            <div class="item">
                <div class="item-name">{{ $item->menu_name }}</div>
                <div class="item-qty">{{ $item->quantity }}x</div>
                <div class="item-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
            </div>
            @if($item->notes)
            <div style="font-size: 10px; color: #666; margin-left: 10px; margin-bottom: 3px;">
                Note: {{ $item->notes }}
            </div>
            @endif
            @endforeach
        </div>
        
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span>Diskon:</span>
                <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="total-row">
                <span>Pajak:</span>
                <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="font-weight: bold; border-top: 1px solid #000; padding-top: 3px;">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="payment-info">
            <div class="total-row">
                <span>Metode Pembayaran:</span>
                <span>{{ strtoupper($order->payment_method ?? 'CASH') }}</span>
            </div>
            <div class="total-row">
                <span>Uang Diterima:</span>
                <span>Rp {{ number_format($order->amount_received, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Kembalian:</span>
                <span>Rp {{ number_format($order->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="footer">
            <div>Terima kasih telah berkunjung</div>
            <div>Silakan datang kembali</div>
            <div style="margin-top: 10px;">
                <div>Kasir: {{ auth()->user()->name }}</div>
                <div>{{ now()->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>
    </div>
</body>
</html> 