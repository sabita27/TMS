<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPLETE DATABASE STATUS ===\n\n";

echo "--- ALL USERS ---\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Role ID: " . ($user->role_id ?? 'NULL') . "\n";
    echo "Status: " . ($user->status ? 'Active' : 'Inactive') . "\n";
    
    // Try to load role
    $role = \App\Models\Role::find($user->role_id);
    echo "Role Name: " . ($role ? $role->name : 'NOT FOUND') . "\n";
    echo "---\n";
}

echo "\n--- ALL ROLES ---\n";
$roles = \App\Models\Role::all();
foreach ($roles as $role) {
    $userCount = \App\Models\User::where('role_id', $role->id)->count();
    echo "ID: {$role->id} | Name: {$role->name} | Users: {$userCount}\n";
}

echo "\n--- TESTING USER MODEL WITH EAGER LOADING ---\n";
$adminUser = \App\Models\User::with('role')->where('email', 'admin@tms.com')->first();
if ($adminUser) {
    echo "Admin Email: {$adminUser->email}\n";
    echo "Admin Role ID: {$adminUser->role_id}\n";
    echo "Admin Role Object: " . ($adminUser->role ? 'EXISTS' : 'NULL') . "\n";
    echo "Admin Role Name: " . ($adminUser->role ? $adminUser->role->name : 'NULL') . "\n";
}
