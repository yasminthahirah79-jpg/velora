<?php
use App\Http\Controllers\{AuthController, CategoryController, DashboardController, KasController, PaymentController, ProductController, PurchaseController, ReportController, SupplierController, UserController};
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,pembelian')->group(function () {
        foreach (['suppliers' => SupplierController::class, 'products' => ProductController::class, 'categories' => CategoryController::class] as $n => $c) {
            Route::resource($n, $c)->only(['index', 'store', 'update', 'destroy'])->parameters([$n => 'id']);
        }
        Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('purchases/{purchase}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');
    });

    Route::middleware('role:admin,keuangan')->group(function () {
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('kas', [KasController::class, 'index'])->name('kas.index');
        Route::post('kas', [KasController::class, 'store'])->name('kas.store');
    });

    Route::get('laporan', [ReportController::class, 'index'])->name('laporan')->middleware('role:admin,keuangan,pimpinan');
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['users' => 'id'])->middleware('role:admin');
});
