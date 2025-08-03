<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    // Jika user belum login, arahkan ke login
    if (!auth()->check()) {
        return redirect()->route('login');
    }
    
    // Jika user sudah login dan role-nya admin, cashier, kitchen, atau waiter
    if (in_array(auth()->user()->role, ['admin', 'cashier', 'kitchen', 'waiter'])) {
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
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
});

// Public Order Routes
Route::prefix('order')->name('order.')->group(function () {
    Route::get('/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('create');
    Route::post('/store', [\App\Http\Controllers\OrderController::class, 'store'])->name('store');
    Route::get('/{order}/show', [\App\Http\Controllers\OrderController::class, 'show'])->name('show');
});
