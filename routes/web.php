<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use Spatie\WelcomeNotification\WelcomesNewUsers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');

Route::group(['middleware' => ['web', WelcomesNewUsers::class,]], function () {
    Route::get('welcome/{user}', [WelcomeController::class, 'showWelcomeForm'])->name('welcome');
    Route::post('welcome/{user}', [WelcomeController::class, 'savePassword']);
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');

    Route::resource('/users', App\Http\Controllers\UsersController::class);
    Route::resource('/roles', App\Http\Controllers\RolesController::class);
    Route::resource('/dishes', App\Http\Controllers\DishesController::class);
    Route::resource('/menus', App\Http\Controllers\MenusController::class);
    Route::resource('/orders', App\Http\Controllers\OrdersController::class);
    Route::get('/users/orders', App\Http\Controllers\UsersOrdersController::class)->name('orders.users.index');
    Route::resource('/departments', App\Http\Controllers\DepartmentsController::class);
    Route::resource('/organizations', App\Http\Controllers\OrganizationsController::class);
    Route::resource('/userTypes', App\Http\Controllers\UserTypesController::class);
    Route::resource('/paymentMethods', App\Http\Controllers\PaymentMethodsController::class);
    Route::resource('/employeeStatuses', App\Http\Controllers\EmployeeStatusesController::class);
});




require __DIR__.'/auth.php';
