<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->call('cache:clear');
echo "Cache cleared: $status\n";
$status = $kernel->call('route:clear');
echo "Route cleared: $status\n";
$status = $kernel->call('permission:cache-reset');
echo "Permission cache reset: $status\n";
