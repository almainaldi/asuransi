<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Sederhanakan halaman utama agar langsung mengarah ke list data pelanggan setelah login
Route::get('/', [CustomerController::class, 'getCustomers'])->middleware(['auth'])->name('dashboard');
Route::get('/dashboard', [CustomerController::class, 'getCustomers'])->middleware(['auth'])->name('dashboard');

// Grup Rute yang membutuhkan Login (Auth)
Route::middleware(['auth'])->group(function () {
    
    // === CRUD CUSTOMER ===
    // Create
    Route::get('/create-customer', [CustomerController::class, 'createCustomer'])->name('create');
    Route::post('/insert-customer', [CustomerController::class, 'insertCustomer'])->name('insert');

    // Update
    Route::get('/update-customer/{customer_id}', [CustomerController::class, 'showFormUpdate'])->name('update');
    Route::patch('/save-customer/{customer_id}', [CustomerController::class, 'updateCustomer'])->name('save');

    // Delete
    Route::delete('/delete-customer/{customer_id}', [CustomerController::class, 'deleteCustomer'])->name('delete');


    // === CRUD TRANSAKSI (BARU) ===
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/insert', [TransaksiController::class, 'insert'])->name('transaksi.insert');
    Route::delete('/transaksi/delete/{id}', [TransaksiController::class, 'delete'])->name('transaksi.delete');

});

require __DIR__.'/auth.php';