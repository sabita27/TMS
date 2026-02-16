<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FINAL FIX FOR ADMIN ROLE ===\n\n";

// Delete all existing roles first
echo "Deleting all existing roles...\n";
\App\Models\Role::truncate();

// Create fresh roles with correct names
echo "Creating fresh roles...\n";
$adminRole = \App\Models\Role::create(['id' => 1, 'name' => 'admin']);
$managerRole = \App\Models\Role::create(['id' => 2, 'name' => 'manager']);
$staffRole = \App\Models\Role::create(['id' => 3, 'name' => 'staff']);
$userRole = \App\Models\Role::create(['id' => 4, 'name' => 'user']);

echo "Created roles:\n";
echo "- Admin (ID: 1)\n";
echo "- Manager (ID: 2)\n";
echo "- Staff (ID: 3)\n";
echo "- User (ID: 4)\n\n";

// Update all users with correct role_ids
echo "Updating users...\n";

$adminUser = \App\Models\User::where('email', 'admin@tms.com')->first();
if ($adminUser) {
    $adminUser->role_id = 1;
    $adminUser->save();
    echo "✓ admin@tms.com → Admin role\n";
}

$managerUser = \App\Models\User::where('email', 'manager@tms.com')->first();
if ($managerUser) {
    $managerUser->role_id = 2;
    $managerUser->save();
    echo "✓ manager@tms.com → Manager role\n";
}

$staffUser = \App\Models\User::where('email', 'staff@tms.com')->first();
if ($staffUser) {
    $staffUser->role_id = 3;
    $staffUser->save();
    echo "✓ staff@tms.com → Staff role\n";
}

$regularUser = \App\Models\User::where('email', 'user@tms.com')->first();
if ($regularUser) {
    $regularUser->role_id = 4;
    $regularUser->save();
    echo "✓ user@tms.com → User role\n";
}

echo "\n=== VERIFICATION ===\n";
$allUsers = \App\Models\User::with('role')->get();
foreach ($allUsers as $user) {
    echo "{$user->email} → Role: " . ($user->role ? $user->role->name : 'NULL') . " (ID: {$user->role_id})\n";
}

echo "\n✅ All fixed! Now clear cache and try logging in again.\n";
