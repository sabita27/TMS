<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ClientController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH (Protected)
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);       // Get logged user
        Route::post('/logout', [AuthController::class, 'logout']); // Logout
    });


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->group(function () {

        Route::get('/', [UserController::class, 'index']);        // List users
        Route::post('/', [UserController::class, 'store']);       // Create user
        Route::get('/{id}', [UserController::class, 'show']);     // View user
        Route::put('/{id}', [UserController::class, 'update']);   // Update user
        Route::delete('/{id}', [UserController::class, 'destroy']); // Delete user

    });

        Route::apiResource('products', ProductController::class);

        Route::apiResource('clients', ClientController::class);

});

