<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Spatie\WelcomeNotification\WelcomesNewUsers;

Route::redirect('/', '/login')->name('login');

Route::group(['middleware' => ['web', WelcomesNewUsers::class,]], function () {
    Route::get('welcome/{user}', [WelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welcome/{user}', [WelcomeController::class, 'savePassword']);
});

$router->group(['namespace' => '\Rap2hpoutre\LaravelLogViewer'], function () use ($router) {
    $router->get('logs', 'LogViewerController@index');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('password.expires');
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::view('activities-log', 'activities.index')->name('activities-log');
    Route::resource('/users', App\Http\Controllers\UsersController::class);
    Route::resource('/suggestions', App\Http\Controllers\SuggestionsBoxController::class);
    Route::resource('/dishes', App\Http\Controllers\DishesController::class);
    Route::resource('/menus', App\Http\Controllers\MenusController::class);
    Route::resource('/orders', App\Http\Controllers\OrdersController::class);
    Route::resource('/organizations', App\Http\Controllers\OrganizationsController::class);
});






require __DIR__ . '/auth.php';