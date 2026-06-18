<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Midtrans Server-to-Server Webhook
Route::post('/midtrans/webhook', [\App\Http\Controllers\Cashier\OrderController::class, 'webhook']);

// Customer Self-Order Routes (Public, protected by token in controller)
Route::get('/order', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'index'])->name('customer.order');
Route::post('/order/checkout', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'checkout'])->name('customer.checkout');
Route::get('/order/{order}/success', [\App\Http\Controllers\Customer\CustomerOrderController::class, 'success'])->name('customer.success');

Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    /** @var \App\Models\User $user */
    $user = $request->user();
    $role_id = $user->role_id;
    return match((int) $role_id) {
        1 => redirect()->route('admin.users.index'),
        2 => redirect()->route('cashier.orders.index'),
        3 => redirect()->route('kitchen.dashboard'),
        default => redirect('/'),
    };
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'role:1'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
    
    // Tables & QR
    Route::resource('tables', \App\Http\Controllers\Admin\TableController::class)->only(['index', 'store', 'destroy']);
    Route::post('tables/{table}/reset-token', [\App\Http\Controllers\Admin\TableController::class, 'resetToken'])->name('tables.reset-token');
    Route::get('tables/{table}/print', [\App\Http\Controllers\Admin\TableController::class, 'printQr'])->name('tables.print');
    
    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'pdf'])->name('reports.pdf');
});

Route::middleware(['auth', 'role:3'])->prefix('kitchen')->name('kitchen.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Kitchen\KitchenController::class, 'index'])->name('dashboard');
    Route::post('/items/{item}/status', [\App\Http\Controllers\Kitchen\KitchenController::class, 'updateStatus'])->name('items.status');
});

Route::middleware(['auth', 'role:1,2'])->prefix('cashier')->name('cashier.')->group(function () {
    // Order routes - accessible by Admin & Kasir
    Route::get('/orders', [\App\Http\Controllers\Cashier\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/active-data', [\App\Http\Controllers\Cashier\OrderController::class, 'activeData'])->name('orders.active.data');
    Route::get('/orders/create', [\App\Http\Controllers\Cashier\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [\App\Http\Controllers\Cashier\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [\App\Http\Controllers\Cashier\OrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}', [\App\Http\Controllers\Cashier\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/{order}/items', [\App\Http\Controllers\Cashier\OrderController::class, 'addItem'])->name('orders.items.store');
    Route::delete('/orders/{order}/items/{item}', [\App\Http\Controllers\Cashier\OrderController::class, 'removeItem'])->name('orders.items.destroy');
    
    // Checkout & Payment
    Route::post('/orders/{order}/checkout', [\App\Http\Controllers\Cashier\OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders/{order}/midtrans-callback', [\App\Http\Controllers\Cashier\OrderController::class, 'midtransCallback'])->name('orders.midtrans.callback');
    Route::get('/orders/{order}/receipt', [\App\Http\Controllers\Cashier\OrderController::class, 'receipt'])->name('orders.receipt');
    Route::post('/orders/{order}/complete', [\App\Http\Controllers\Cashier\OrderController::class, 'complete'])->name('orders.complete');

    // Sales History
    Route::get('/sales', [\App\Http\Controllers\Cashier\SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/{order}', [\App\Http\Controllers\Cashier\SalesController::class, 'show'])->name('sales.show');
});

use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
