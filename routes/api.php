<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MenuController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AccessCardController;

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

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('menus', MenuController::class);
    Route::apiResource('access-cards', AccessCardController::class);
    Route::post('validate/order', [OrderController::class, 'validateOrder']);
    Route::post('reload/access-card', [AccessCardController::class, 'reloadAccessCard']);
});


Route::post('/login', [AuthController::class, 'login']);