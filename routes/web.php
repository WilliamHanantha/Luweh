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

Route::get('/order-activity', function () {
    return view('order');
});

// Page 2: Food Hero
Route::get('/food-hero', function () {
    return view('foodhero');
});

