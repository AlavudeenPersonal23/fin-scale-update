<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('index');

Auth::routes();

//Basics
Route::get('home', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index']);
Route::get('reset-password', [App\Http\Controllers\HomeController::class, 'passwordReset']);
Route::post('reset-password', [App\Http\Controllers\HomeController::class, 'passwordReseting'])->name('password-change');
Route::get('set-pagination/{page_size}', [App\Http\Controllers\HomeController::class, 'setPageSize']);

//Shed Management
Route::get('sheds', [App\Http\Controllers\ShedController::class, 'index'])->name('shed-index');
Route::get('sheds/create', [App\Http\Controllers\ShedController::class, 'create'])->name('shed-create');
Route::post('sheds/save', [App\Http\Controllers\ShedController::class, 'save'])->name('shed-save');
Route::get('sheds/edit/{id}', [App\Http\Controllers\ShedController::class, 'edit'])->name('shed-edit');
Route::post('sheds/update', [App\Http\Controllers\ShedController::class, 'update'])->name('shed-update');
Route::get('sheds/delete/{id}', [App\Http\Controllers\ShedController::class, 'delete'])->name('shed-delete');
Route::get('sheds/search', [App\Http\Controllers\ShedController::class, 'search'])->name('sheds-search');

//Vehicle Management
Route::get('vehicles', [App\Http\Controllers\VehicleController::class, 'index'])->name('vehicle-index');
Route::get('vehicles/create', [App\Http\Controllers\VehicleController::class, 'create'])->name('vehicle-create');
Route::post('vehicles/save', [App\Http\Controllers\VehicleController::class, 'save'])->name('vehicle-save');
Route::get('vehicles/edit/{id}', [App\Http\Controllers\VehicleController::class, 'edit'])->name('vehicle-edit');
Route::post('vehicles/update', [App\Http\Controllers\VehicleController::class, 'update'])->name('vehicle-update');
Route::get('vehicles/delete/{id}', [App\Http\Controllers\VehicleController::class, 'delete'])->name('vehicle-delete');
Route::get('vehicles/search', [App\Http\Controllers\VehicleController::class, 'search'])->name('vehicles-search');

//Waste Management
Route::get('waste-types', [App\Http\Controllers\WasteTypeController::class, 'index'])->name('waste-type-index');
Route::get('waste-types/create', [App\Http\Controllers\WasteTypeController::class, 'create'])->name('waste-type-create');
Route::post('waste-types/save', [App\Http\Controllers\WasteTypeController::class, 'save'])->name('waste-type-save');
Route::get('waste-types/edit/{id}', [App\Http\Controllers\WasteTypeController::class, 'edit'])->name('waste-type-edit');
Route::post('waste-types/update', [App\Http\Controllers\WasteTypeController::class, 'update'])->name('waste-type-update');
Route::get('waste-types/delete/{id}', [App\Http\Controllers\WasteTypeController::class, 'delete'])->name('waste-type-delete');
Route::get('waste-types/search', [App\Http\Controllers\WasteTypeController::class, 'search'])->name('waste-type-search');

//Farmer Management
Route::get('farmers', [App\Http\Controllers\FarmerController::class, 'index'])->name('farmer-index');
Route::get('farmers/create', [App\Http\Controllers\FarmerController::class, 'create'])->name('farmer-create');
Route::post('farmers/save', [App\Http\Controllers\FarmerController::class, 'save'])->name('farmer-save');
Route::get('farmers/edit/{id}', [App\Http\Controllers\FarmerController::class, 'edit'])->name('farmer-edit');
Route::post('farmers/update', [App\Http\Controllers\FarmerController::class, 'update'])->name('farmer-update');
Route::get('farmers/delete/{id}', [App\Http\Controllers\FarmerController::class, 'delete'])->name('farmer-delete');
Route::get('farmers/search', [App\Http\Controllers\FarmerController::class, 'search'])->name('farmer-search');
Route::post('farmers/get-shed-farmer', [App\Http\Controllers\FarmerController::class, 'getShedFarmer'])->name('shed-farmer-search');

//User Management
Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('user-index');
Route::get('users/create', [App\Http\Controllers\UserController::class, 'create'])->name('user-create');
Route::post('users/save', [App\Http\Controllers\UserController::class, 'save'])->name('user-save');
Route::get('users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('user-edit');
Route::post('users/update', [App\Http\Controllers\UserController::class, 'update'])->name('user-update');
Route::get('users/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user-delete');
Route::get('users/search', [App\Http\Controllers\UserController::class, 'search'])->name('user-search');

//Weignments Management
Route::get('weignments', [App\Http\Controllers\WeignmentController::class, 'index'])->name('weignment-index');
Route::get('weignments/create', [App\Http\Controllers\WeignmentController::class, 'create'])->name('weignment-create');
Route::post('weignments/save', [App\Http\Controllers\WeignmentController::class, 'save'])->name('weignment-save');
Route::get('weignments/edit/{id}', [App\Http\Controllers\WeignmentController::class, 'edit'])->name('weignment-edit');
Route::post('weignments/update', [App\Http\Controllers\WeignmentController::class, 'update'])->name('weignment-update');
Route::get('weignments/delete/{id}', [App\Http\Controllers\WeignmentController::class, 'delete'])->name('weignment-delete');
Route::get('weignments/search', [App\Http\Controllers\WeignmentController::class, 'search'])->name('weignment-search');

//Reports
Route::get('reports', [App\Http\Controllers\ReportController::class, 'newIndex'])->name('new-report-index');
Route::post('reports', [App\Http\Controllers\ReportController::class, 'getNewIndex'])->name('get-new-report');
Route::get('report-two', [App\Http\Controllers\ReportController::class, 'newIndexTwo'])->name('new-report-two-index');
Route::post('report-two', [App\Http\Controllers\ReportController::class, 'getNewIndexTwo'])->name('get-new-report-two');

Route::get('reports/{report}', [App\Http\Controllers\ReportController::class, 'index'])->name('report-index');
Route::post('reports/shed-abstract-report/get-report', [App\Http\Controllers\ReportController::class, 'getReport'])->name('shed-abstract-report');
Route::post('reports/shed-detail-report/get-report', [App\Http\Controllers\ReportController::class, 'getReport'])->name('shed-detail-report');
Route::post('reports/slip-report/get-report', [App\Http\Controllers\ReportController::class, 'getReport'])->name('slip-report');
Route::get('slip-report-pdf', [App\Http\Controllers\ReportController::class, 'getSlipReport'])->name('get-slip-report-pdf');


