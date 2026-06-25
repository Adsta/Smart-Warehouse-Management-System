<?php

use App\Http\Controllers\MovementOrderController;
use Illuminate\Support\Facades\Route;

Route::apiResource('movement-orders', MovementOrderController::class)->only(['store']);
Route::patch('movement-orders/{movementOrder}/complete', [MovementOrderController::class, 'complete']);
