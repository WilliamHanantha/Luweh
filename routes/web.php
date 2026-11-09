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

Route::get('/your-menu', function () {
    return view('fnb-owner.yourMenu');
});

Route::group(['prefix' => 'fnb-owner'], function () {
    Route::get('/', [App\Http\Controllers\DaftarMakananController::class, 'index']);
    Route::post('/store', [App\Http\Controllers\DaftarMakananController::class, 'store']);
    Route::get('/create', [App\Http\Controllers\DaftarMakananController::class, 'create']);
    Route::delete('/{id}', [App\Http\Controllers\DaftarMakananController::class, 'destroy']);
    Route::get('/order', [App\Http\Controllers\DaftarMakananController::class, 'order']);
});

Route::get('/order-activity', function () {
    return view('fnb-owner.order');
});

// Page 2: Food Hero
Route::get('/food-hero', function () {
    return view('courier.foodhero');
});

Route::get('/milestone', function () {
    return view('milestone');
});

Route::get('/redeem-points', function () {
    return view('courier.redeempoints');
});
