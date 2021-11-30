<?php

use App\Http\Controllers\API\AccessCardsController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MenusController;
use App\Http\Controllers\API\OrdersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders/confirm-order', [OrdersController::class, 'confirm']);
    Route::apiResource('orders', OrdersController::class);
    Route::apiResource('menus', MenusController::class);
    Route::apiResource('cards', AccessCardsController::class);
});


Route::post('/login', [AuthController::class, 'login']);
