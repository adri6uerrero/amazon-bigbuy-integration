<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderAdminController;

Route::get('/', [OrderAdminController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderAdminController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderAdminController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [OrderAdminController::class, 'show'])->name('orders.show');
Route::post('/orders/{id}/retry', [OrderAdminController::class, 'retry'])->name('orders.retry');
Route::post('/orders/{id}/mark-shipped', [OrderAdminController::class, 'markShipped'])->name('orders.markShipped');
Route::post('/orders/{id}/cancel', [OrderAdminController::class, 'cancel'])->name('orders.cancel');
Route::post('/orders/{id}/add-note', [OrderAdminController::class, 'addNote'])->name('orders.addNote');
Route::post('/orders/{id}/reopen', [OrderAdminController::class, 'reopen'])->name('orders.reopen');
Route::post('/orders/{id}/resend-email', [OrderAdminController::class, 'resendEmail'])->name('orders.resendEmail');

use App\Http\Controllers\CustomerAdminController;
Route::get('/customers', [CustomerAdminController::class, 'index'])->name('customers.index');
Route::get('/customers/{id}', [CustomerAdminController::class, 'show'])->name('customers.show');
