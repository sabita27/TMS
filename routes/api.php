<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\GlobalSettingController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\TicketPriorityController;
use App\Http\Controllers\Api\TicketStatusController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TicketController;
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

    Route::prefix('designations')->group(function () {

        Route::get('/', [DesignationController::class, 'index']);          // List
        Route::post('/', [DesignationController::class, 'store']);         // Create
        Route::get('/{id}', [DesignationController::class, 'show']);       // View
        Route::post('/{id}', [DesignationController::class, 'update']);    // Update
        Route::delete('/{id}', [DesignationController::class, 'destroy']); // Delete

    });

    Route::prefix('positions')->group(function () {

        Route::get('/', [PositionController::class, 'index']);          // List
        Route::post('/', [PositionController::class, 'store']);         // Create
        Route::get('/{id}', [PositionController::class, 'show']);       // View
        Route::post('/{id}', [PositionController::class, 'update']);    // Update
        Route::delete('/{id}', [PositionController::class, 'destroy']); // Delete

    });

    Route::prefix('categories')->group(function () {

        Route::get('/', [CategoryController::class, 'index']);          // List
        Route::post('/', [CategoryController::class, 'store']);         // Create
        Route::get('/{id}', [CategoryController::class, 'show']);       // View
        Route::post('/{id}', [CategoryController::class, 'update']);    // Update
        Route::delete('/{id}', [CategoryController::class, 'destroy']); // Delete

    });

    Route::prefix('subcategories')->group(function () {

        // 🔥 IMPORTANT (category based)
        Route::get('/category/{id}', [SubcategoryController::class, 'getByCategory']);

        Route::get('/', [SubcategoryController::class, 'index']);          // List
        Route::post('/', [SubcategoryController::class, 'store']);         // Create
        Route::get('/view/{id}', [SubcategoryController::class, 'show']);  // Single
        Route::post('/{id}', [SubcategoryController::class, 'update']);    // Update
        Route::delete('/{id}', [SubcategoryController::class, 'destroy']); // Delete

    });

    Route::prefix('ticket-priorities')->group(function () {
        Route::get('/', [TicketPriorityController::class, 'index']);          // List
        Route::post('/', [TicketPriorityController::class, 'store']);         // Create
        Route::get('/{id}', [TicketPriorityController::class, 'show']);       // Single
        Route::post('/{id}', [TicketPriorityController::class, 'update']);    // Update
        Route::delete('/{id}', [TicketPriorityController::class, 'destroy']); // Delete
    });

    Route::prefix('ticket-statuses')->group(function () {
        Route::get('/', [TicketStatusController::class, 'index']);          // List
        Route::post('/', [TicketStatusController::class, 'store']);         // Create
        Route::get('/{id}', [TicketStatusController::class, 'show']);       // Single
        Route::post('/{id}', [TicketStatusController::class, 'update']);    // Update
        Route::delete('/{id}', [TicketStatusController::class, 'destroy']); // Delete

    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);          // List roles
        Route::post('/', [RoleController::class, 'store']);         // Create role
        Route::get('/{id}', [RoleController::class, 'show']);       // Single role
        Route::post('/{id}', [RoleController::class, 'update']);    // Update role
        Route::delete('/{id}', [RoleController::class, 'destroy']); // Delete role
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);          // List
        Route::post('/', [PermissionController::class, 'store']);         // Create
        Route::get('/{id}', [PermissionController::class, 'show']);       // Single
        Route::post('/{id}', [PermissionController::class, 'update']);    // Update
        Route::delete('/{id}', [PermissionController::class, 'destroy']); // Delete
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'getProfile']);
        Route::post('/update', [ProfileController::class, 'updateProfile']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
    });

    Route::prefix('tickets')->group(function () {
        Route::post('/', [TicketController::class, 'store']); // Create ticket
        Route::get('/my-tickets', [TicketController::class, 'myTickets']); // My tickets
        Route::get('/{id}', [TicketController::class, 'show']); // View single ticket
    });
});
