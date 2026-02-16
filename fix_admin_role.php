<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING ADMIN ROLE NAME ===\n";

// Find the superadmin role and rename it to admin
$superadminRole = \App\Models\Role::where('name', 'superadmin')->first();
if ($superadminRole) {
    $superadminRole->name = 'admin';
    $superadminRole->save();
    echo "Renamed 'superadmin' to 'admin'\n";
} else {
    echo "No 'superadmin' role found\n";
}

// Verify
echo "\n=== VERIFICATION ===\n";
$adminUser = \App\Models\User::where('email', 'admin@tms.com')->with('role')->first();
if ($adminUser) {
    echo "Admin user: {$adminUser->email}\n";
    echo "Role: " . ($adminUser->role ? $adminUser->role->name : 'NULL') . "\n";
}

echo "\n=== ALL ROLES ===\n";
$roles = \App\Models\Role::all();
foreach ($roles as $role) {
    echo "ID: {$role->id} - Name: {$role->name}\n";
}
