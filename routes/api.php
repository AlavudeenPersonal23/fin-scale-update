<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ApiController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-by-id', [AuthController::class, 'loginById']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::get('/invalid', [AuthController::class, 'invalidToken'])->name('invalid-token');
Route::get('/get-user-details', [ApiController::class, 'getUser']);
Route::get('/get-today-weignments', [ApiController::class, 'getTodayWeignments']);
Route::get('/get-pending-weignments', [ApiController::class, 'getPendingWeignments']);
Route::get('/get-weignment/{id}', [ApiController::class, 'getWeignment']);
Route::get('/get-vehicles', [ApiController::class, 'getVehicles']);
Route::get('/get-sheds', [ApiController::class, 'getSheds']);
Route::get('/get-wastes', [ApiController::class, 'getWastes']);
Route::get('/get-wastes-defaults/{id}', [ApiController::class, 'getWastesDefaults']);
Route::get('/get-wastes-defaults-by-shed/{id}', [ApiController::class, 'getWastesDefaultsByShed']);
Route::get('/get-farmers', [ApiController::class, 'getFarmers']);
Route::get('/get-grades', [ApiController::class, 'getGrades']);
Route::post('/create-weighment', [ApiController::class, 'createWeignment']);
Route::post('/update-weighment', [ApiController::class, 'updateWeighment']);

//Reports
Route::post('/summary-report', [ApiController::class, 'summaryReport']);
Route::post('/detailed-report', [ApiController::class, 'detailedReport']);

Route::post('/weignment-history', [ApiController::class, 'weignmentHistory']);
