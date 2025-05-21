<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ConfigController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de gestión de pedidos
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/notes', [OrderController::class, 'addNote'])->name('orders.addNote');
    
    // Rutas para procesamiento de pedidos
    Route::get('/orders/process/amazon', [OrderController::class, 'showProcessForm'])->name('orders.process.form');
    Route::post('/orders/process/amazon', [OrderController::class, 'processOrder'])->name('orders.process');
    
    // Rutas para informes y estadísticas
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate/{type}', [ReportController::class, 'generateReport'])->name('reports.generate');
    
    // Rutas para gestión de clientes
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    
    // Rutas para gestión de productos
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/sync', [ProductController::class, 'sync'])->name('products.sync');
    
    // Rutas para el panel de configuración
    Route::get('/config', [ConfigController::class, 'index'])->name('config.index');
    Route::post('/config/amazon', [ConfigController::class, 'updateAmazon'])->name('config.update.amazon');
    Route::post('/config/bigbuy', [ConfigController::class, 'updateBigbuy'])->name('config.update.bigbuy');
    Route::post('/config/sync', [ConfigController::class, 'updateSync'])->name('config.update.sync');
    Route::post('/config/general', [ConfigController::class, 'updateGeneral'])->name('config.update.general');
    Route::post('/config/test-connection', [ConfigController::class, 'testConnection'])->name('config.test.connection');
    Route::post('/config/sync-now', [ConfigController::class, 'syncNow'])->name('config.sync.now');
});

require __DIR__.'/auth.php';

Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
