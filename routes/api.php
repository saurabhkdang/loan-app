<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\FrontController;
use App\Http\Controllers\API\BackendController;
use App\Http\Controllers\API\UserController;

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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login'])->middleware(['throttle:login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/getAllLoanRequest', [FrontController::class, 'getAllLoanRequest']);
    Route::post('/loan_request', [FrontController::class, 'loan_request'])->middleware(['throttle:loan_request']);
    Route::post('/payment', [FrontController::class, 'payment']);
    Route::get('/transaction-history',[FrontController::class, 'transaction_history']);
});

Route::post('admin/login', [UserController::class, 'login'])->middleware(['throttle:login']);;
Route::middleware(['auth:sanctum','admin'])->group(function () {
    Route::get('/admin/getLoanRequest', [BackendController::class, 'getLoanRequest']);
    Route::post('/admin/updateLoanStatus',[BackendController::class, 'updateLoanStatus']);
});