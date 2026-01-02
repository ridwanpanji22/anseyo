<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        /* Table Styling mirip gambar */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000; /* Garis hitam tegas */
            padding: 6px 8px;
        }
        
        /* Header Table Color (Peach/Orange muda) */
        thead th {
            background-color: #ffe6cc; 
            color: #000;
            font-weight: bold;
            text-align: center;
        }

        /* Footer Table Color */
        tfoot td {
            background-color: #ffe6cc;
            font-weight: bold;
        }

        /* Alignment */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        /* Column Widths (Optional adjustment) */
        .col-no { width: 5%; }
        .col-date { width: 15%; }
        .col-name { width: 30%; }
        .col-price { width: 15%; }
        .col-qty { width: 10%; }
        .col-total { width: 25%; }
    </style>
</head>
<body>
    <div class="header">
        <!-- Ganti dengan Nama Restoran Anda -->
        <h2 style="color: #d9534f;">ANSEYO RESTO</h2> 
        <h3>Laporan Penjualan</h3>
        <p>{{ $periodText }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-date">Tanggal</th>
                <th class="col-name">Item Menu</th>
                <th class="col-price">Harga Item</th>
                <th class="col-qty">Qty Terjual</th>
                <th class="col-total">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->order_date)->format('d/m/Y') }}</td>
                <td class="text-left">{{ $item->menu_name }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($item->total_qty) }}</td>
                <td class="text-right">
                    Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data penjualan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-center">Total</td>
                <td class="text-center">{{ number_format($totalQty) }}</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
