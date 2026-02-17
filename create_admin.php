<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$adminRole = Role::where('name', 'admin')->first();
if ($adminRole) {
    $user = User::updateOrCreate(
        ['email' => 'admin@tms.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
            'status' => true
        ]
    );

    echo "Admin User Set Up:\n";
    echo "Email: " . $user->email . "\n";
    echo "Password: password\n";
    echo "Role ID: " . $user->role_id . "\n";
    echo "Status: " . ($user->status ? 'Active' : 'Inactive') . "\n";
} else {
    echo "Error: 'admin' role not found in database. Run migrations/seeders first.\n";
}
