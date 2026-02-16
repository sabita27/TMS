<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // API for dynamic categories (Common for all roles)
    Route::get('/get-subcategories/{category}', [MasterController::class, 'getSubCategories'])->name('api.subcategories');
    Route::get('/get-positions/{designation}', [AdminController::class, 'getPositions'])->name('api.positions');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Users Management
        Route::controller(AdminController::class)->group(function () {
            Route::get('/users', 'users')->name('admin.users');
            Route::post('/users', 'storeUser')->name('admin.users.store');
            Route::get('/users/{user}/edit', 'editUser')->name('admin.users.edit');
            Route::put('/users/{user}', 'updateUser')->name('admin.users.update');
            Route::delete('/users/{user}', 'destroyUser')->name('admin.users.delete');
        });
        
        // Clients
        Route::controller(MasterController::class)->group(function () {
            Route::get('/clients', 'clients')->name('admin.clients');
            Route::post('/clients', 'storeClient')->name('admin.clients.store');
            Route::get('/clients/{client}/edit', 'editClient')->name('admin.clients.edit');
            Route::put('/clients/{client}', 'updateClient')->name('admin.clients.update');
            Route::delete('/clients/{client}', 'destroyClient')->name('admin.clients.delete');
        });

        // Roles
        Route::controller(\App\Http\Controllers\RoleController::class)->group(function () {
            Route::get('/roles', 'index')->name('admin.roles');
            Route::post('/roles', 'store')->name('admin.roles.store');
            Route::get('/roles/{role}/edit', 'edit')->name('admin.roles.edit');
            Route::put('/roles/{role}', 'update')->name('admin.roles.update');
            Route::delete('/roles/{role}', 'destroy')->name('admin.roles.delete');
        });

        // Designations
        Route::controller(\App\Http\Controllers\DesignationController::class)->group(function () {
            Route::get('/designations', 'index')->name('admin.designations');
            Route::post('/designations', 'store')->name('admin.designations.store');
            Route::get('/designations/{designation}/edit', 'edit')->name('admin.designations.edit');
            Route::put('/designations/{designation}', 'update')->name('admin.designations.update');
            Route::delete('/designations/{designation}', 'destroy')->name('admin.designations.delete');
        });

        // Positions
        Route::controller(\App\Http\Controllers\PositionController::class)->group(function () {
            Route::get('/positions', 'index')->name('admin.positions');
            Route::post('/positions', 'store')->name('admin.positions.store');
            Route::get('/positions/{position}/edit', 'edit')->name('admin.positions.edit');
            Route::put('/positions/{position}', 'update')->name('admin.positions.update');
            Route::delete('/positions/{position}', 'destroy')->name('admin.positions.delete');
        });
        // Projects
        Route::controller(\App\Http\Controllers\ProjectController::class)->group(function () {
            Route::get('/projects', 'index')->name('admin.projects');
            Route::post('/projects', 'store')->name('admin.projects.store');
            Route::get('/projects/{project}/edit', 'edit')->name('admin.projects.edit');
            Route::put('/projects/{project}', 'update')->name('admin.projects.update');
            Route::delete('/projects/{project}', 'destroy')->name('admin.projects.delete');
        });
        
        Route::get('/products', [MasterController::class, 'products'])->name('admin.products');
        Route::post('/products', [MasterController::class, 'storeProduct'])->name('admin.products.store');
        Route::get('/products/{product}/edit', [MasterController::class, 'editProduct'])->name('admin.products.edit');
        Route::put('/products/{product}', [MasterController::class, 'updateProduct'])->name('admin.products.update');
        Route::delete('/products/{product}', [MasterController::class, 'destroyProduct'])->name('admin.products.delete');
        
        Route::get('/categories', [MasterController::class, 'categories'])->name('admin.categories');
        Route::post('/categories', [MasterController::class, 'storeCategory'])->name('admin.categories.store');
        Route::get('/categories/{category}/edit', [MasterController::class, 'editCategory'])->name('admin.categories.edit');
        Route::put('/categories/{category}', [MasterController::class, 'updateCategory'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [MasterController::class, 'destroyCategory'])->name('admin.categories.delete');
        
        Route::get('/subcategories', [MasterController::class, 'subCategories'])->name('admin.subcategories');
        Route::post('/subcategories', [MasterController::class, 'storeSubCategory'])->name('admin.subcategories.store');
        Route::get('/subcategories/{subcategory}/edit', [MasterController::class, 'editSubCategory'])->name('admin.subcategories.edit');
        Route::put('/subcategories/{subcategory}', [MasterController::class, 'updateSubCategory'])->name('admin.subcategories.update');
        Route::delete('/subcategories/{subcategory}', [MasterController::class, 'destroySubCategory'])->name('admin.subcategories.delete');
    });

    // User Routes
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/products', [UserController::class, 'products'])->name('user.products');
        Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
        Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('user.profile.password');
        
        Route::get('/tickets', [TicketController::class, 'userTickets'])->name('user.tickets');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('user.tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('user.tickets.store');
        Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('user.tickets.close');
    });

    // Staff Routes
    Route::middleware(['role:staff'])->prefix('staff')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
        Route::get('/designation', [StaffController::class, 'designation'])->name('staff.designation');
        Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('staff.tickets.status');
    });

    // Manager Routes
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
        Route::get('/tickets', [TicketController::class, 'managerTickets'])->name('manager.tickets');
        Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('manager.tickets.assign');
        Route::post('/tickets/{ticket}/forward', [TicketController::class, 'forward'])->name('manager.tickets.forward');
    });
});
