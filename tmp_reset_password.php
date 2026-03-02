<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::all();
foreach ($users as $u) {
    echo "User: " . $u->email . " - Role: " . implode(', ', $u->getRoleNames()->toArray()) . " - Status: " . $u->status . "\n";
    if ($u->email === 'test@gmail.com') {
        $check = Hash::check('password123', $u->password);
        echo "   Check 'password123' for test@gmail.com: " . ($check ? "PASS" : "FAIL") . "\n";
    }
}
