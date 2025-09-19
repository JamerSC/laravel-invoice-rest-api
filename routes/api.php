<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\FileUploadController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\PayfusionController;
use App\Http\Controllers\Api\V1\SmsController;
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
    Route::post('/register', [AuthController::class, 'register']);  
    Route::post('/login', [AuthController::class, 'login']);

    // private endpoint
    Route::middleware('auth:sanctum')->group(function(){
        // logout endpoint
        Route::post('/logout', [AuthController::class,'logout']);

        // users api resource endpoint
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{userId}', [UserController::class, 'show']);
        Route::put('/users/{userId}', [UserController::class, 'update']);
        Route::patch('/users/{userId}', [UserController::class, 'update']); 
        Route::delete('/users/{userId}', [UserController::class, 'destroy']);
        //Route::apiResource('/users', UserController::class);
        
        // customers http methods
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::get('/customers/{customerId}', [CustomerController::class, 'show']);
        Route::put('/customers/{customerId}', [CustomerController::class, 'update']);
        Route::patch('/customers/{customerId}', [CustomerController::class, 'update']);
        Route::delete('/customers/{customerId}', [CustomerController::class, 'destroy']);
        //Route::apiResource('/customers', CustomerController::class); // all in endpoint
        
        // invoices http methods
        Route::get('/invoices', [InvoiceController::class, 'index']);
        Route::post('/invoices', [InvoiceController::class, 'store']);
        Route::get('/invoices/{invoiceId}', [InvoiceController::class, 'show']);
        Route::put('/invoices/{invoiceId}', [InvoiceController::class, 'update']);
        Route::patch('/invoices/{invoiceId}', [InvoiceController::class, 'update']);
        Route::delete('/invoices/{invoiceId}', [InvoiceController::class, 'destroy']);
        // custom
        Route::patch('/invoices/{invoiceId}/mark-as-paid', [InvoiceController::class, 'markAsPaid']);
        Route::patch('/invoices/{invoiceId}/mark-as-void', [InvoiceController::class, 'markAsVoid']);
        //Route::apiResource('/invoices', InvoiceController::class); // all in endpoint

        // File upload endpoint
        Route::post('/upload-single-file', [FileUploadController::class, 'uploadSingleFile']);
        Route::post('/upload-multiple-file', [FileUploadController::class, 'uploadMultipleFile']);

        // for testing
        // Route::get('/profile', function (Request $request) {
        // return response()->json([
        //     'name'  => $request->user()->name,
        //     'email' => $request->user()->email,
        //     ]);
        // });

        // Payfusion API Routes
        // Token
        route::post('/payfusion-tokens', [PayfusionController::class, 'createPaymentToken']);
        route::get('/payfusion-tokens/{requestId}', [PayfusionController::class, 'getPaymentToken']);
        // Payment
        route::post('/payfusion-payments', [PayfusionController::class, 'createPaymentRequest']);
        route::get('/payfusion-payments/{requestId}', [PayfusionController::class, 'getPaymentRequest']);
        route::post('/payfusion-payments/{requestId}/capture', [PayfusionController::class, 'capturePaymentRequest']);
        // Invoice
        route::get('/payfusion-invoices', [PayfusionController::class, 'listOfInvoices']);
        Route::get('/payfusion-invoices/{invoiceId}', [PayfusionController::class,'showInvoice']);
        route::post('/payfusion-invoices', [PayfusionController::class, 'createInvoice']);

        // Infobip API route
        route::post('/infobip-send-sms', [SmsController::class, 'sendMessage']);
    });
});

