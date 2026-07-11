<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);


Route::get('/', function () {
    return redirect('/login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/items/categories', [ItemController::class, 'getCategories'])->name('admin.items.categories');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard.index');
    Route::get('/data-barang', [ItemController::class, 'index'])->name('admin.data-barang.index');

    // Endpoint AJAX (Asynchronous CRUD & Live Search) - khusus Admin
    Route::get('/items', [ItemController::class, 'getData'])->name('admin.items.data');
    Route::get('/items/{id}', [ItemController::class, 'show'])->name('admin.items.show');
    Route::post('/store', [ItemController::class, 'store'])->name('admin.items.store');
    Route::put('/update/{id}', [ItemController::class, 'update'])->name('admin.items.update');
    Route::delete('/delete/{id}', [ItemController::class, 'delete'])->name('admin.items.delete');

    // ====== MODUL DATA MASTER: Kategori Barang ======
    Route::get('/master/kategori', [CategoryController::class, 'index'])->name('admin.kategori.index');
    Route::get('/master/kategori-data', [CategoryController::class, 'getData'])->name('admin.kategori.data');
    Route::get('/master/kategori/{id}', [CategoryController::class, 'show'])->name('admin.kategori.show');
    Route::post('/master/kategori', [CategoryController::class, 'store'])->name('admin.kategori.store');
    Route::put('/master/kategori/{id}', [CategoryController::class, 'update'])->name('admin.kategori.update');
    Route::delete('/master/kategori/{id}', [CategoryController::class, 'delete'])->name('admin.kategori.delete');

    // ====== MODUL TRANSAKSI: Barang Masuk & Keluar ======
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('admin.transaksi.index');
    Route::get('/transaksi-data', [TransactionController::class, 'getData'])->name('admin.transaksi.data');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('admin.transaksi.store');
    Route::delete('/transaksi/{id}', [TransactionController::class, 'delete'])->name('admin.transaksi.delete');
        });
        
    Route::get('/scan-barang/{kode_barang}', [ItemController::class, 'scanDetail'])->name('scan.detail');
    Route::middleware(['auth', 'role:user'])->prefix('user')->group(function () {
    Route::get('/inventaris', [ItemController::class, 'userIndex'])->name('user.index');

    // Endpoint AJAX (Read Only + Live Search) - khusus User
    Route::get('/items', [ItemController::class, 'getData'])->name('user.items.data');

    // // ====== MODUL TRANSAKSI (Read Only) - khusus User ======
    // Route::get('/transaksi', [TransactionController::class, 'userIndex'])->name('user.transaksi.index');
    // Route::get('/transaksi-data', [TransactionController::class, 'getData'])->name('user.transaksi.data');
});