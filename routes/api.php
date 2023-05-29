<?php

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
    Route::post('/orders/confirm-order', [App\Http\Controllers\API\CompleteOrderController::class, 'complete']); // Deprecated
    Route::post('/orders/complete-order', [App\Http\Controllers\API\MarkOrderAsCompleted::class, 'update']);
    Route::post('orders/cancel-validation', [App\Http\Controllers\API\MarkOrderAsCompleted::class, 'markAsConfirmed']);
    Route::apiResource('orders', App\Http\Controllers\API\OrdersController::class);
    Route::get('completed/orders', [App\Http\Controllers\API\OrdersController::class, 'orderCompleted']);
    Route::apiResource('menus', App\Http\Controllers\API\MenusController::class);
    Route::post('/cards/link-temporary-card', App\Http\Controllers\API\LinkTemporaryCard::class);
    Route::apiResource('cards', App\Http\Controllers\API\AccessCardsController::class);
    Route::post('cards/current/assign', [App\Http\Controllers\API\AccessCardsController::class, 'assignCurrentCarrd']);
    Route::post('cards/current/temporary', [App\Http\Controllers\API\AccessCardsController::class, 'assignTemporaryCard']);
    Route::apiResource('users', App\Http\Controllers\API\UsersController::class);
    Route::post('users/profile-update', [App\Http\Controllers\API\UsersController::class, 'updateProfile']);
    Route::post('users/change-password', [App\Http\Controllers\API\UsersController::class, 'changePassword']);
    Route::post('users/current', [App\Http\Controllers\API\UsersController::class, 'userAuthenticate']);
    Route::apiResource('dishes', App\Http\Controllers\API\DishesController::class);
    Route::post('reload/cards', [App\Http\Controllers\API\AccessCardsController::class, 'reloadAccessCard']);
    Route::post('cards/current', [App\Http\Controllers\API\AccessCardsController::class, 'currentAccessCard']);
});

Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
