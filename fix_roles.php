<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING ROLES ===\n";

// Ensure all roles exist
$roleNames = ['admin', 'manager', 'staff', 'user'];
foreach ($roleNames as $roleName) {
    $role = \App\Models\Role::firstOrCreate(['name' => $roleName]);
    echo "Role '{$roleName}' - ID: {$role->id}\n";
}

echo "\n=== ASSIGNING ROLES TO USERS ===\n";

// Get roles
$adminRole = \App\Models\Role::where('name', 'admin')->first();
$managerRole = \App\Models\Role::where('name', 'manager')->first();
$staffRole = \App\Models\Role::where('name', 'staff')->first();
$userRole = \App\Models\Role::where('name', 'user')->first();

// Assign roles to users
$users = [
    'admin@tms.com' => $adminRole->id,
    'manager@tms.com' => $managerRole->id,
    'staff@tms.com' => $staffRole->id,
    'user@tms.com' => $userRole->id,
];

foreach ($users as $email => $roleId) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        $user->role_id = $roleId;
        $user->save();
        echo "Updated {$email} with role_id: {$roleId}\n";
    } else {
        echo "User {$email} not found!\n";
    }
}

echo "\n=== VERIFICATION ===\n";
$allUsers = \App\Models\User::with('role')->get();
foreach ($allUsers as $user) {
    echo "{$user->email} - Role: " . ($user->role ? $user->role->name : 'NULL') . "\n";
}
