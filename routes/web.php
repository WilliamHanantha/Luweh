<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// UC 01
Route::get('/customer-home', [App\Http\Controllers\RestoranController::class, 'index']);

// UC 02
Route::get('/order-now', [App\Http\Controllers\OrderNowController::class, 'index']);
Route::post('/order-now/item/{id}/tambah', [App\Http\Controllers\OrderNowController::class, 'tambah']);
Route::post('/order-now/item/{id}/kurang', [App\Http\Controllers\OrderNowController::class, 'kurang']);

Route::get('/your-menu', [App\Http\Controllers\DaftarMakananController::class, 'index']);
Route::get('/set-quantity/{id}', [App\Http\Controllers\DaftarMakananController::class, 'setQuantity']);
Route::post('/set-quantity/{id}', [App\Http\Controllers\DaftarMakananController::class, 'confirmQuantity']);

Route::group(['prefix' => 'fnb-owner'], function () {
    Route::get('/', [App\Http\Controllers\DaftarMakananController::class, 'index']);
    Route::post('/store', [App\Http\Controllers\DaftarMakananController::class, 'store']);
    Route::get('/create', [App\Http\Controllers\DaftarMakananController::class, 'create']);
    Route::get('/{id}/set-quantity', [App\Http\Controllers\DaftarMakananController::class, 'setQuantity'])->name('makanan.set-quantity');
    Route::post('/{id}/confirm-quantity', [App\Http\Controllers\DaftarMakananController::class, 'confirmQuantity'])->name('makanan.confirm-quantity');
    Route::post('/{id}/toggle-status', [App\Http\Controllers\DaftarMakananController::class, 'toggleStatus'])->name('makanan.toggle-status');
    Route::delete('/{id}', [App\Http\Controllers\DaftarMakananController::class, 'destroy']);
    Route::get('/order', [App\Http\Controllers\DaftarMakananController::class, 'order'])->name('fnb-owner.order');
    Route::get('/history', [App\Http\Controllers\DaftarMakananController::class, 'history'])->name('fnb-owner.history');
    // AJAX: tandai item pesanan sudah diambil driver
    Route::post('/order/item/{id}/pickup', [App\Http\Controllers\DaftarMakananController::class, 'updateStatusDriver'])->name('fnb-owner.order.pickup');
});



// Courier Routes
Route::group(['prefix' => 'courier'], function () {
    Route::get('/', function () {
        return view('courier.courierhome');
    })->name('courier.home');

    Route::get('/food-hero', [\App\Http\Controllers\CourierController::class, 'foodHero'])->name('courier.foodhero');
    Route::post('/order/{id}/pick', [\App\Http\Controllers\CourierController::class, 'pickOrder'])->name('courier.order.pick');

    Route::get('/activity', [\App\Http\Controllers\CourierController::class, 'activity'])->name('courier.activity');
    Route::post('/order/{id}/deliver', [\App\Http\Controllers\CourierController::class, 'deliverOrder'])->name('courier.order.deliver');

    Route::get('/redeem-points', function () {
        return view('courier.redeempoints');
    })->name('courier.redeem');
});
