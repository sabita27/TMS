<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::find(14);
if ($u) {
    $u->email = 'admin@tms.com';
    $u->password = Illuminate\Support\Facades\Hash::make('password');
    $u->save();
    echo "Admin updated successfully!\n";
} else {
    echo "User 14 not found!\n";
}
