<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\FileUploadController;
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
    Route::post('/register', [AuthController::class, 'register'])->summary('User Registration Auth');
    Route::post('/login', [AuthController::class, 'login'])->summary('User Login Auth');

    // private endpoint
    Route::middleware('auth:sanctum')->group(function(){
        // logout endpoint
        Route::post('/logout', [AuthController::class,'logout'])->summary('User Logout Auth');

        // users api resource endpoint
        Route::get('/users', [UserController::class, 'index'])->summary('Get All Users');
        Route::post('/users', [UserController::class, 'store'])->summary('Create new User');
        Route::get('/users/{userId}', [UserController::class, 'show'])->summary('Get User by ID');
        Route::put('/users/{userId}', [UserController::class, 'update'])->summary('Update User reference by ID');
        Route::patch('/users/{userId}', [UserController::class, 'update'])->summary('Patch User reference by ID');
        Route::delete('/users/{userId}', [UserController::class, 'destroy'])->summary('Delete User by ID');
        //Route::apiResource('/users', UserController::class);
        
        // customers http methods
        Route::get('/customers', [CustomerController::class, 'index'])->summary('Get All Customers with Pagination, Sort, Filter, Search, & Optional');
        Route::post('/customers', [CustomerController::class, 'store'])->summary('Create new Customer');
        Route::get('/customers/{customerId}', [CustomerController::class, 'show'])->summary('Get Customer by ID');
        Route::put('/customers/{customerId}', [CustomerController::class, 'update'])->summary('Update Customer reference by ID');
        Route::patch('/customers/{customerId}', [CustomerController::class, 'update'])->summary('Patch Customer reference by ID');
        Route::delete('/customers/{customerId}', [CustomerController::class, 'destroy'])->summary('Delete Customer by ID');
        //Route::apiResource('/customers', CustomerController::class); // all in endpoint
        
        // invoices http methods
        Route::get('/invoices', [InvoiceController::class, 'index'])->summary('Get All Invoices with Pagination, Sort, & Filter');
        Route::post('/invoices', [InvoiceController::class, 'store'])->summary('Create new Invoices');
        Route::get('/invoices/{invoiceId}', [InvoiceController::class, 'show'])->summary('Get Invoices by ID');
        Route::put('/invoices/{invoiceId}', [InvoiceController::class, 'update'])->summary('Update Invoices reference by ID');
        Route::patch('/invoices/{invoiceId}', [InvoiceController::class, 'update'])->summary('Patch Invoices reference by ID');
        Route::delete('/invoices/{invoiceId}', [InvoiceController::class, 'destroy'])->summary('Delete Invoices by ID');
        // custom
        Route::patch('/invoices/{invoiceId}/mark-as-paid', [InvoiceController::class, 'markAsPaid'])->summary('Update Invoice status to Paid reference by ID');
        Route::patch('/invoices/{invoiceId}/mark-as-void', [InvoiceController::class, 'markAsVoid'])->summary('Update Invoice status to Void reference by ID');
        //Route::apiResource('/invoices', InvoiceController::class); // all in endpoint

        // File upload endpoint
        Route::post('/upload', [FileUploadController::class, 'upload'])->summary('Upload Single File');
        Route::post('/upload-multiple', [FileUploadController::class, 'uploadMultiple'])->summary('Upload Multiple Files');

        // for testing
        // Route::get('/profile', function (Request $request) {
        // return response()->json([
        //     'name'  => $request->user()->name,
        //     'email' => $request->user()->email,
        //     ]);
        // });
    });
});

