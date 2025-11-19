<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $order->receipt_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
            background: white;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .restaurant-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-number {
            font-size: 10px;
            color: #666;
        }
        .order-info {
            margin-bottom: 10px;
        }
        .order-info div {
            margin-bottom: 3px;
        }
        .items {
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
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
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .payment-info {
            margin-bottom: 10px;
        }
        .payment-info div {
            margin-bottom: 3px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px dashed #ccc;
            padding-top: 10px;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .receipt {
                border: none;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <div class="restaurant-name">ANSEYO RESTAURANT</div>
            <div>Jl. Contoh No. 123</div>
            <div>Telp: (021) 1234-5678</div>
            <div class="receipt-number">{{ $order->receipt_number }}</div>
            <div>{{ $order->paid_at->format('d/m/Y H:i') }}</div>
        </div>

        <div class="order-info">
            <div><strong>Order:</strong> {{ $order->order_number }}</div>
            <div><strong>Meja:</strong> {{ $order->table->number ?? 'N/A' }}</div>
            @if($order->customer_name)
            <div><strong>Pemesan:</strong> {{ $order->customer_name }}</div>
            @endif
        </div>

        <div class="items">
            @foreach($order->orderItems as $item)
            <div class="item">
                <div class="item-name">{{ $item->menu_name }}</div>
                <div class="item-qty">{{ $item->quantity }}x</div>
                <div class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>

        <div class="totals">
            <div class="total-row">
                <div>Subtotal:</div>
                <div>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div>PPN (11%):</div>
                <div>Rp {{ number_format($order->tax, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div><strong>TOTAL:</strong></div>
                <div><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></div>
            </div>
        </div>

        <div class="payment-info">
            <div><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</div>
            <div><strong>Uang Diterima:</strong> Rp {{ number_format($order->amount_received, 0, ',', '.') }}</div>
            <div><strong>Kembalian:</strong> Rp {{ number_format($order->change_amount, 0, ',', '.') }}</div>
        </div>

        <div class="footer">
            <div>Terima kasih telah berkunjung</div>
            <div>Semoga hari Anda menyenangkan!</div>
            <div style="margin-top: 10px;">
                <div>Kasir: {{ auth()->user()->name }}</div>
                <div>{{ now()->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html> 