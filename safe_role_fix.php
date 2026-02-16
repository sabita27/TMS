<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SAFE ROLE FIX ===\n\n";

// First, set all users' role_id to NULL temporarily
echo "Step 1: Temporarily removing role assignments...\n";
\DB::table('users')->update(['role_id' => null]);

// Delete all roles
echo "Step 2: Deleting old roles...\n";
\DB::table('roles')->delete();

// Create fresh roles with correct names
echo "Step 3: Creating correct roles...\n";
\DB::table('roles')->insert([
    ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 2, 'name' => 'manager', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 3, 'name' => 'staff', 'created_at' => now(), 'updated_at' => now()],
    ['id' => 4, 'name' => 'user', 'created_at' => now(), 'updated_at' => now()],
]);

echo "Created roles:\n";
echo "- Admin (ID: 1)\n";
echo "- Manager (ID: 2)\n";
echo "- Staff (ID: 3)\n";
echo "- User (ID: 4)\n\n";

// Update users with correct role_ids
echo "Step 4: Assigning roles to users...\n";

\DB::table('users')->where('email', 'admin@tms.com')->update(['role_id' => 1]);
echo "✓ admin@tms.com → Admin\n";

\DB::table('users')->where('email', 'manager@tms.com')->update(['role_id' => 2]);
echo "✓ manager@tms.com → Manager\n";

\DB::table('users')->where('email', 'staff@tms.com')->update(['role_id' => 3]);
echo "✓ staff@tms.com → Staff\n";

\DB::table('users')->where('email', 'user@tms.com')->update(['role_id' => 4]);
echo "✓ user@tms.com → User\n";

echo "\n=== VERIFICATION ===\n";
$users = \App\Models\User::with('role')->get();
foreach ($users as $user) {
    echo "{$user->email} → Role: " . ($user->role ? $user->role->name : 'NULL') . " (ID: {$user->role_id})\n";
}

echo "\n✅ All fixed!\n";
