<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// api/v1 - initial end point
// v1 = version 1
Route::prefix('v1')->group(function () {
    // public endpoint
    Route::post('/register', [AuthController::class,'register']);
    Route::post('/login', [AuthController::class,'login']);

    // private endpoint
    Route::middleware('auth:sanctum')->group(function(){
        // logout endpoint
        Route::post('/logout', [AuthController::class,'logout']);
        // users api resource endpoint
        Route::apiResource('/users', UserController::class);
        // customers api resource endpoint
        Route::apiResource('/customers', CustomerController::class);
        // invoices api resource endpoint
        Route::apiResource('/invoices', InvoiceController::class);
    });
});

