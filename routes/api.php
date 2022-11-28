<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Cors;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::get('is-valid', 'isValid');
    Route::post('refresh', 'refresh');

});

Route::controller(UserController::class)->group(function () {
    Route::get('user', 'index');
    Route::patch('user', 'update');
    Route::get('login-info/{email}', 'getLoginInfo');
    Route::post('user', 'store');

});

Route::controller(CostumerController::class)->group(function () {
    Route::get('costumer', 'index');
    Route::patch('costumer', 'update');
    Route::post('costumer', 'store');

});

Route::controller(ProfessionalController::class)->group(function () {
    Route::get('professional', 'index');
    Route::patch('professional', 'update');
    Route::post('professional', 'store');

});

Route::get('/teste', [AuthController::class, 'index']);
