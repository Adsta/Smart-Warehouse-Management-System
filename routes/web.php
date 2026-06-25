<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ZoneController;
use App\Http\Controllers\Web\LocationController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\MovementOrderWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('zones', ZoneController::class)->except(['show']);
Route::resource('locations', LocationController::class)->except(['show']);
Route::resource('products', ProductController::class)->except(['show']);
Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');

Route::resource('movement-orders', MovementOrderWebController::class)->except(['show', 'edit', 'update']);
Route::patch('movement-orders/{movementOrder}/complete', [MovementOrderWebController::class, 'complete'])->name('movement-orders.complete');
Route::patch('movement-orders/{movementOrder}/cancel', [MovementOrderWebController::class, 'cancel'])->name('movement-orders.cancel');
