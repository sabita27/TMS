<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CURRENT USER STATUS ===\n";

// Check all users with their roles
$users = \App\Models\User::with('role')->get();
foreach ($users as $user) {
    echo "\nEmail: {$user->email}\n";
    echo "Role ID: " . ($user->role_id ?? 'NULL') . "\n";
    echo "Role Name: " . ($user->role ? $user->role->name : 'NULL') . "\n";
    echo "Status: " . ($user->status ? 'Active' : 'Inactive') . "\n";
    
    // Check if password is correct
    if ($user->email === 'admin@tms.com') {
        $passwordCheck = \Illuminate\Support\Facades\Hash::check('password', $user->password);
        echo "Password 'password' works: " . ($passwordCheck ? 'YES' : 'NO') . "\n";
    }
}

echo "\n=== ALL ROLES ===\n";
$roles = \App\Models\Role::all();
foreach ($roles as $role) {
    echo "ID: {$role->id} - Name: {$role->name}\n";
}
