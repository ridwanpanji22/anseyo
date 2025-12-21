<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    // Jika user belum login, arahkan ke login
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    // Redirect berdasarkan role
    $user = auth()->user();
    
    if ($user->role === 'kitchen') {
        return redirect()->route('kitchen.dashboard');
    } elseif ($user->role === 'cashier') {
        return redirect()->route('cashier.dashboard');
    } elseif (in_array($user->role, ['admin'])) {
        return redirect()->route('admin.dashboard');
    }
    
    // Jika user sudah login dengan role lain, tampilkan welcome
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Menu Management
    Route::resource('menu', \App\Http\Controllers\Admin\MenuController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('discounts', \App\Http\Controllers\Admin\DiscountController::class);
    
    // Order Management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    
    // Staff Management
    Route::resource('staff', \App\Http\Controllers\Admin\StaffController::class);
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    
    // Table Management
    Route::resource('tables', \App\Http\Controllers\Admin\TableController::class);
    Route::get('/tables/{table}/qr-code', [\App\Http\Controllers\Admin\TableController::class, 'generateQrCode'])->name('tables.qr-code');
    Route::put('/tables/{table}/status', [\App\Http\Controllers\Admin\TableController::class, 'updateStatus'])->name('tables.update-status');
    Route::get('/tables/status-overview', [\App\Http\Controllers\Admin\TableController::class, 'statusOverview'])->name('tables.status-overview');
    
    // AJAX Routes for Tables
    Route::post('/tables/{table}/status', [\App\Http\Controllers\Admin\TableController::class, 'updateStatus'])->name('tables.update-status-ajax');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
});

    // Kitchen Dashboard - Allow kitchen and admin roles
    Route::middleware(['auth', 'kitchen'])->prefix('kitchen')->name('kitchen.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\KitchenController::class, 'dashboard'])->name('dashboard');
        Route::post('/orders/{order}/start-preparing', [\App\Http\Controllers\KitchenController::class, 'startPreparing'])->name('orders.start-preparing');
        Route::post('/orders/{order}/mark-ready', [\App\Http\Controllers\KitchenController::class, 'markReady'])->name('orders.mark-ready');
        Route::post('/orders/{order}/cancel', [\App\Http\Controllers\KitchenController::class, 'cancel'])->name('orders.cancel');
        Route::get('/orders/by-status', [\App\Http\Controllers\KitchenController::class, 'getOrdersByStatus'])->name('orders.by-status');
        Route::get('/orders/{order}/show', [\App\Http\Controllers\KitchenController::class, 'showOrder'])->name('orders.show');
        
        // Menu Availability
        Route::get('/menus', [\App\Http\Controllers\KitchenController::class, 'menuList'])->name('menus.index');
        Route::patch('/menus/{menu}/toggle', [\App\Http\Controllers\KitchenController::class, 'toggleAvailability'])->name('menus.toggle');
    });

    // Cashier Dashboard - Allow cashier and admin roles
    Route::middleware(['auth', 'cashier'])->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\CashierController::class, 'dashboard'])->name('dashboard');
        Route::post('/orders/{order}/mark-paid', [\App\Http\Controllers\CashierController::class, 'markPaid'])->name('orders.mark-paid');
        Route::get('/orders/by-payment-status', [\App\Http\Controllers\CashierController::class, 'getOrdersByPaymentStatus'])->name('orders.by-payment-status');
        Route::get('/orders/{order}/show', [\App\Http\Controllers\CashierController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{order}/receipt', [\App\Http\Controllers\CashierController::class, 'printReceipt'])->name('orders.receipt');
        Route::get('/tables/status', [\App\Http\Controllers\CashierController::class, 'getTablesStatus'])->name('tables.status');
        Route::get('/revenue/daily', [\App\Http\Controllers\CashierController::class, 'getDailyRevenue'])->name('revenue.daily');
    });

// Public Order Routes
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/{table}/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('create');
    Route::post('/{table}/store', [\App\Http\Controllers\OrderController::class, 'store'])->name('store');
    Route::get('/{order}/show', [\App\Http\Controllers\OrderController::class, 'show'])->name('show');
    Route::post('/{order}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('cancel');
});
