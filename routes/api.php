<?php

use Illuminate\Support\Facades\Route;
use \App\Customer\Profile\Controllers\UserController;
use \App\Customer\Accounts\Controllers\AccountController;

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
//public endpoint for authentication
Route::prefix('v1')->group(function () {
    Route::post('customers', [UserController::class, 'createUserProfile']);
    Route::post('customers/tokens', [UserController::class, 'login']);
});
//protected endpoints for the application
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('customers/{customerId}/accounts', [UserController::class, 'getUserBankAccounts']);
    Route::post('customers/{customerId}/accounts', [AccountController::class, 'createBankAccount']);
    Route::get('customers/accounts/{accountId}', [AccountController::class, 'getBankAccountById']);
    Route::put('customers/accounts/{accountId}', [AccountController::class, 'updateBankAccountDetails']);
    Route::get('customers/accounts/{accountId}/balance', [AccountController::class, 'getBankAccountBalance']);
});
