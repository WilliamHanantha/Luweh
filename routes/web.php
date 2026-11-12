<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenukaranPoinController;

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
    Route::get('/{id}/edit', [App\Http\Controllers\DaftarMakananController::class, 'edit'])->name('makanan.edit');
    Route::put('/{id}', [App\Http\Controllers\DaftarMakananController::class, 'update'])->name('makanan.update');
    Route::get('/{id}/set-quantity', [App\Http\Controllers\DaftarMakananController::class, 'setQuantity'])->name('makanan.set-quantity');
    Route::post('/{id}/confirm-quantity', [App\Http\Controllers\DaftarMakananController::class, 'confirmQuantity'])->name('makanan.confirm-quantity');
    Route::post('/{id}/toggle-status', [App\Http\Controllers\DaftarMakananController::class, 'toggleStatus'])->name('makanan.toggle-status');
    Route::delete('/{id}', [App\Http\Controllers\DaftarMakananController::class, 'destroy'])->name('makanan.destroy');
    Route::get('/order', [App\Http\Controllers\PesananController::class, 'order'])->name('fnb-owner.order');
    Route::get('/history', [App\Http\Controllers\PesananController::class, 'history'])->name('fnb-owner.history');
    // AJAX: tandai pesanan sudah diambil driver (ubah status pesanan menjadi 'picked up')
    Route::post('/order/pesanan/{id}/pickup', [App\Http\Controllers\PesananController::class, 'updateStatusDriver'])->name('fnb-owner.order.pickup');
});



// Courier Routes
Route::group(['prefix' => 'courier'], function () {
    Route::get('/', function () {
        return view('courier.courierhome');
    })->name('courier.home');

    Route::get('/food-hero', [\App\Http\Controllers\CourierController::class, 'foodHero'])->name('courier.foodhero');
    Route::post('/order/{id}/pick', [\App\Http\Controllers\CourierController::class, 'pickOrder'])->name('courier.order.pick');

    Route::get('/activity', [\App\Http\Controllers\CourierController::class, 'activity'])->name('courier.activity');
    Route::get('/activity/history', [\App\Http\Controllers\CourierController::class, 'history'])->name('courier.history');
    Route::get('/history', [\App\Http\Controllers\CourierController::class, 'history']);
    Route::post('/order/{id}/deliver', [\App\Http\Controllers\CourierController::class, 'deliverOrder'])->name('courier.order.deliver');

    // UC14 - Redeem Points
    Route::get('/redeem-points', [PenukaranPoinController::class, 'index'])->name('courier.redeem');
    Route::get('/redeem-points/{id}/confirm', [PenukaranPoinController::class, 'confirm'])->whereNumber('id')->name('courier.redeem.confirm');
    Route::post('/redeem-points/{id}/redeem', [PenukaranPoinController::class, 'redeem'])->whereNumber('id')->name('courier.redeem.store');
    Route::get('/redeem-points/receipt/{id}', [PenukaranPoinController::class, 'receipt'])->whereNumber('id')->name('courier.redeem.receipt');

});
