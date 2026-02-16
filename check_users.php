<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== USERS TABLE ===\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "Email: {$user->email}\n";
    echo "Role ID: {$user->role_id}\n";
    echo "Role Name: " . ($user->role ? $user->role->name : 'NULL') . "\n";
    echo "Status: {$user->status}\n";
    echo "---\n";
}

echo "\n=== ROLES TABLE ===\n";
$roles = \App\Models\Role::all();
foreach ($roles as $role) {
    echo "ID: {$role->id} - Name: {$role->name}\n";
}
