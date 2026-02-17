<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$adminRole = Role::where('name', 'admin')->first();
if (!$adminRole) {
    echo "Admin Role NOT FOUND in DB.\n";
    // Check if roles table is populated
    $firstRole = Role::first();
    if ($firstRole) {
        echo "Roles table has content. First role: " . $firstRole->name . "\n";
    } else {
        echo "Roles table is EMPTY.\n";
    }
} else {
    echo "Admin Role ID: " . $adminRole->id . "\n";
    $admins = User::where('role_id', $adminRole->id)->get();
    if ($admins->count() > 0) {
        echo "Found Admins:\n";
        foreach ($admins as $u) {
            echo "- " . $u->email . " (Pass match 'password': " . (Hash::check('password', $u->password) ? 'YES' : 'NO') . ")\n";
        }
    } else {
        echo "No users found with Admin Role ID.\n";
    }
}
