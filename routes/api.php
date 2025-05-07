<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AmazonController;
use App\Http\Controllers\BigBuyController;
use App\Http\Controllers\OrderController;

Route::get('/amazon/orders/{id}', [AmazonController::class, 'show']);
Route::post('/bigbuy/orders', [BigBuyController::class, 'store']);
Route::post('/orders/process', [OrderController::class, 'processOrder']);
