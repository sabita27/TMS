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
Route::get('/manager/login', [AuthController::class, 'showManagerLogin'])->name('manager.login');
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'getLogout']);

Route::middleware(['auth'])->group(function () {
    // Shared Profile Routes (Accessible by all roles)
    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile')->name('user.profile');
        Route::put('/profile', 'updateProfile')->name('user.profile.update');
        Route::put('/profile/password', 'updatePassword')->name('user.profile.password');
    });

    // Redirect old profile URL to new one
    Route::get('/user/profile', function () {
        return redirect()->route('user.profile');
    });

    // API for dynamic categories (Common for all roles)
    Route::get('/get-subcategories/{category}', [MasterController::class, 'getSubCategories'])->name('api.subcategories');
    Route::get('/get-positions/{designation}', [AdminController::class, 'getPositions'])->name('api.positions');

    // Routes shared by Admin and Manager
    Route::middleware(['role:admin|manager'])->prefix('admin')->group(function () {
        // Clients
        Route::controller(MasterController::class)->group(function () {
            Route::get('/clients', 'clients')->name('admin.clients');
            Route::post('/clients', 'storeClient')->name('admin.clients.store');
            Route::get('/clients/{client}/edit', 'editClient')->name('admin.clients.edit');
            Route::put('/clients/{client}', 'updateClient')->name('admin.clients.update');
            Route::delete('/clients/{client}', 'destroyClient')->name('admin.clients.delete');
            // Service Reminder Email
            Route::post('/client-services/{clientService}/send-reminder', 'sendReminder')->name('admin.client_services.send_reminder');
        });

        // Projects
        Route::controller(MasterController::class)->group(function () {
            Route::get('/projects', 'projects')->name('admin.projects');
            Route::post('/projects', 'storeProject')->name('admin.projects.store');
            Route::get('/projects/{project}/edit', 'editProject')->name('admin.projects.edit');
            Route::put('/projects/{project}', 'updateProject')->name('admin.projects.update');
            Route::delete('/projects/{project}', 'destroyProject')->name('admin.projects.delete');
        });

        // Services
        Route::controller(MasterController::class)->group(function () {
            Route::get('/services', 'services')->name('admin.services');
            Route::post('/services', 'storeService')->name('admin.services.store');
            Route::get('/services/{service}/edit', 'editService')->name('admin.services.edit');
            Route::put('/services/{service}', 'updateService')->name('admin.services.update');
            Route::delete('/services/{service}', 'destroyService')->name('admin.services.delete');
        });

        Route::get('/products', [MasterController::class, 'products'])->name('admin.products');
        Route::post('/products', [MasterController::class, 'storeProduct'])->name('admin.products.store');
        Route::get('/products/{product}/edit', [MasterController::class, 'editProduct'])->name('admin.products.edit');
        Route::put('/products/{product}', [MasterController::class, 'updateProduct'])->name('admin.products.update');
        Route::delete('/products/{product}', [MasterController::class, 'destroyProduct'])->name('admin.products.delete');
    });

    // Admin-Only Routes
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
        
        // Roles
        Route::controller(\App\Http\Controllers\RoleController::class)->group(function () {
            Route::get('/roles', 'index')->name('admin.roles');
            Route::post('/roles', 'store')->name('admin.roles.store');
            Route::get('/roles/{role}/edit', 'edit')->name('admin.roles.edit');
            Route::put('/roles/{role}', 'update')->name('admin.roles.update');
            Route::delete('/roles/{role}', 'destroy')->name('admin.roles.delete');
        });

        // Permissions
        Route::controller(\App\Http\Controllers\PermissionController::class)->group(function () {
            Route::get('/permissions', 'index')->name('admin.permissions');
            Route::post('/permissions', 'store')->name('admin.permissions.store');
            Route::get('/permissions/{permission}/edit', 'edit')->name('admin.permissions.edit');
            Route::put('/permissions/{permission}', 'update')->name('admin.permissions.update');
            Route::delete('/permissions/{permission}', 'destroy')->name('admin.permissions.delete');
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

        // Ticket Statuses
        Route::controller(MasterController::class)->group(function () {
            Route::get('/ticket-statuses', 'ticketStatuses')->name('admin.ticket_statuses');
            Route::post('/ticket-statuses', 'storeTicketStatus')->name('admin.ticket_statuses.store');
            Route::get('/ticket-statuses/{status}/edit', 'editTicketStatus')->name('admin.ticket_statuses.edit');
            Route::put('/ticket-statuses/{status}', 'updateTicketStatus')->name('admin.ticket_statuses.update');
            Route::delete('/ticket-statuses/{status}', 'destroyTicketStatus')->name('admin.ticket_statuses.delete');
        });

        // Ticket Priorities
        Route::controller(MasterController::class)->group(function () {
            Route::get('/ticket-priorities', 'ticketPriorities')->name('admin.ticket_priorities');
            Route::post('/ticket-priorities', 'storeTicketPriority')->name('admin.ticket_priorities.store');
            Route::get('/ticket-priorities/{priority}/edit', 'editTicketPriority')->name('admin.ticket_priorities.edit');
            Route::put('/ticket-priorities/{priority}', 'updateTicketPriority')->name('admin.ticket_priorities.update');
            Route::delete('/ticket-priorities/{priority}', 'destroyTicketPriority')->name('admin.ticket_priorities.delete');
        });

        // Unified Setup
        Route::get('/setup', [MasterController::class, 'setup'])->name('admin.setup');
        Route::post('/setup/settings', [MasterController::class, 'updateSettings'])->name('admin.setup.settings');
    });

    // User Routes
    Route::middleware(['role:user'])->prefix('user')->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
        Route::get('/products', [UserController::class, 'products'])->name('user.products');
        
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
