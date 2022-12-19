<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\MaterialController;
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
    Route::get('costumer-search/{search}', 'getCostumerSearch');

});

Route::controller(ProfessionalController::class)->group(function () {
    Route::get('professional', 'index');
    Route::patch('professional', 'update');
    Route::post('professional', 'store');
    Route::get('professional-data/{id}', 'getProfessionalData');
    Route::get('professional-address/{id}', 'getProfessionalAddress');
    Route::get('professional-bank-data/{id}', 'getProfessionalBankData');
    Route::get('professional-payment/{id}', 'getProfessionalPayment');
    Route::get('professional-full-data/{id}', 'getProfessionalFullData');
    Route::get('professional-search/{search}', 'getProfessionalSearch');

});

Route::controller(CategoryController::class)->group(function () {
    Route::get('category', 'index');
    Route::get('check-category/{category}', 'checkCategory');
    Route::post('category', 'store');
    Route::post('sub-category', 'storeSubCategory');
    Route::get('sub-category/{id}', 'getSubCategory');
});

Route::controller(BudgetController::class)->group(function () {
    Route::post('budget', 'store');
    Route::get('budget', 'index');
    Route::get('budget-image', 'getImage');
    Route::get('budget-search/{costumer}', 'search');
    Route::delete('budget/{id}', 'destroy');
});

Route::controller(MaterialController::class)->group(function () {
    Route::get('material-products', 'getAllProducts');
    Route::post('material', 'addProduct');
});

Route::get('/teste', [AuthController::class, 'index']);
