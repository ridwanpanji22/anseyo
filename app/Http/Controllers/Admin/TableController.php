<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tables = Table::withCount('orders')
            ->orderBy('number')
            ->paginate(15);
            
        // Get status overview
        $statusOverview = [
            'available' => Table::where('status', 'available')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'maintenance' => Table::where('status', 'maintenance')->count(),
        ];
            
        return view('admin.tables.index', compact('tables', 'statusOverview'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.tables.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:50|unique:tables',
            'capacity' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['qr_code'] = 'table-' . Str::slug($request->number);
        $data['is_active'] = $request->has('is_active');

        Table::create($data);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table): View
    {
        $table->load(['orders' => function($query) {
            $query->latest()->take(10);
        }]);
        return view('admin.tables.show', compact('table'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table): View
    {
        return view('admin.tables.edit', compact('table'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        $request->validate([
            'number' => 'required|string|max:50|unique:tables,number,' . $table->id,
            'capacity' => 'required|integer|min:1|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $table->update($data);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        // Check if table has active orders
        if ($table->orders()->whereIn('status', ['pending', 'preparing', 'ready'])->count() > 0) {
            return redirect()->route('admin.tables.index')
                ->with('error', 'Meja tidak dapat dihapus karena masih memiliki pesanan aktif');
        }
        
        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Meja berhasil dihapus');
    }

    /**
     * Generate QR code for table.
     */
    public function generateQrCode(Table $table)
    {
        $baseUrl = config('app.qr_base_url');
        $qrCodeUrl = $baseUrl . '/order/create?table=' . urlencode($table->number);
        
        return view('admin.tables.qr-code', compact('table', 'qrCodeUrl'));
    }

    /**
     * Update table status.
     */
    public function updateStatus(Request $request, Table $table)
    {
        $request->validate([
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        $table->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status meja berhasil diperbarui',
            'table' => $table->fresh()
        ]);
    }

    /**
     * Get table status overview.
     */
    public function statusOverview()
    {
        $tables = Table::active()->get();
        $statusCounts = [
            'available' => $tables->where('status', 'available')->count(),
            'occupied' => $tables->where('status', 'occupied')->count(),
            'reserved' => $tables->where('status', 'reserved')->count(),
            'maintenance' => $tables->where('status', 'maintenance')->count(),
        ];

        return view('admin.tables.status-overview', compact('tables', 'statusCounts'));
    }
}
