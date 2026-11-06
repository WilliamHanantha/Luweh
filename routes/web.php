<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/customer-home', function () {
    return view('customer.customer-home');
});

Route::get('/your-menu', function () {
    return view('fnb-owner.yourMenu');
});

Route::group(['prefix' => 'fnb-owner'], function () {
    Route::get('/', [App\Http\Controllers\DaftarMakananController::class, 'index']);
    Route::post('/', [App\Http\Controllers\DaftarMakananController::class, 'store']);
    Route::get('/create', [App\Http\Controllers\DaftarMakananController::class, 'create']);
    Route::delete('/{id}', [App\Http\Controllers\DaftarMakananController::class, 'destroy']);
});

Route::get('/order-activity', function () {
    return view('fnb-owner.order');
});

// Page 2: Food Hero
Route::get('/food-hero', function () {
    return view('courier.foodhero');
});

Route::get('/redeem-points', function () {
    return view('courier.redeempoints');
});
