<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('user', [UserController::class, 'show']);
    Route::put('user', [UserController::class, 'update']);
    Route::delete('user', [UserController::class, 'destroy']);
    
    Route::get('addresses', [AddressController::class, 'index']);
    Route::get('address/{id}', [AddressController::class, 'show']);
    Route::post('address', [AddressController::class, 'store']);
    Route::put('address/{id}', [AddressController::class, 'update']);
    Route::delete('address/{id}', [AddressController::class, 'destroy']);
});