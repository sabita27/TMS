<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GlobalSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        Route::get('/me', [AuthController::class, 'me']);          // Get logged user
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

        Route::get('/', [UserController::class, 'index']);          // List users
        Route::post('/', [UserController::class, 'store']);         // Create user
        Route::get('/{id}', [UserController::class, 'show']);       // View user
        Route::put('/{id}', [UserController::class, 'update']);     // Update user
        Route::delete('/{id}', [UserController::class, 'destroy']); // Delete user

    });

    Route::apiResource('products', ProductController::class);
    Route::get('categories', [ProductController::class, 'categories']);
    Route::get('subcategories/{category_id}', [ProductController::class, 'subCategories']);

    Route::prefix('projects')->group(function () {

        Route::get('/statuses', [ProjectController::class, 'getStatuses']);
        Route::get('/priorities', [ProjectController::class, 'getPriorities']);

        Route::get('/', [ProjectController::class, 'index']);
        Route::post('/', [ProjectController::class, 'store']);
        Route::get('/{id}', [ProjectController::class, 'show']);
        Route::post('/{id}', [ProjectController::class, 'update']);
        Route::delete('/{id}', [ProjectController::class, 'destroy']);
    });

    Route::prefix('services')->group(function () {

        Route::get('/', [ServiceController::class, 'index']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::get('/{id}', [ServiceController::class, 'show'])->where('id', '[0-9]+');
        Route::put('/{id}', [ServiceController::class, 'update'])->where('id', '[0-9]+');
        Route::delete('/{id}', [ServiceController::class, 'destroy'])->where('id', '[0-9]+');

    });

    Route::prefix('clients')->group(function () {

        Route::get('/', [ClientController::class, 'index']);
        Route::post('/', [ClientController::class, 'store']);
        Route::get('/{id}', [ClientController::class, 'show']);
        Route::post('/{id}', [ClientController::class, 'update']);
        Route::delete('/{id}', [ClientController::class, 'destroy']);

    });

    Route::prefix('settings')->group(function () {

    Route::get('/', [GlobalSettingController::class, 'index']);  
    Route::post('/', [GlobalSettingController::class, 'update']);  

    Route::get('/{section}', [GlobalSettingController::class, 'getBySection']);  

});

});
