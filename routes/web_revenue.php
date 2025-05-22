<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RevenueController;

// Rutas para el informe de ingresos
Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
