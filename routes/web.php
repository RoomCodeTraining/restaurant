<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
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
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('password.expires');
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');
    Route::get('reporting/orders', App\Http\Controllers\ReportingController::class)->name('reporting.orders');
    Route::view('reporting/check-in-breakfast', 'reporting.check-breakfast')->name('reporting.check.breakfast');
    Route::get('reporting/accounts', App\Http\Controllers\ReportingController::class)->name('reporting.account');
    Route::view('users-import', 'users.import')->name('users-import');

    Route::resource('/users', App\Http\Controllers\UsersController::class);
    Route::resource('/suggestions-box', App\Http\Controllers\SuggestionsBoxController::class);
    Route::get('/statistics/dishes', [App\Http\Controllers\StatsController::class, 'dishStats'])->name('dishes.stats');
    Route::get('/statistics/users-type', [App\Http\Controllers\StatsController::class, 'usersTypeStats'])->name('users.stats');
    Route::resource('/roles', App\Http\Controllers\RolesController::class);
    Route::resource('/dishes', App\Http\Controllers\DishesController::class);
    Route::resource('/menus', App\Http\Controllers\MenusController::class);
    Route::get('weekly-orders/summary',[App\Http\Controllers\OrdersSummaryController::class, 'weeklyOrder'])->name('orders.summary');
    Route::get('today-orders/summary', [App\Http\Controllers\OrdersSummaryController::class, 'todayOrder'])->name('today.orders.summary');
    Route::view('check-in-breakfast', 'orders.check-breakfast')->name('check-in-breakfast');
    Route::resource('/orders', App\Http\Controllers\OrdersController::class);
    Route::resource('/departments', App\Http\Controllers\DepartmentsController::class);
    Route::resource('/organizations', App\Http\Controllers\OrganizationsController::class);
    Route::resource('/userTypes', App\Http\Controllers\UserTypesController::class);
    Route::resource('/paymentMethods', App\Http\Controllers\PaymentMethodsController::class);
    Route::resource('/employeeStatuses', App\Http\Controllers\EmployeeStatusesController::class);
});




require __DIR__.'/auth.php';
