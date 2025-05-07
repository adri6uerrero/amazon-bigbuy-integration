<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderAdminController;

Route::get('/', [OrderAdminController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [OrderAdminController::class, 'show'])->name('orders.show');
Route::post('/orders/{id}/retry', [OrderAdminController::class, 'retry'])->name('orders.retry');
