<?php
// Quick diagnostic - place at c:\xampp\htdocs\TMS\tmp_diag_assign.php
// Run via: http://localhost/TMS/tmp_diag_assign.php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tickets = \App\Models\Ticket::all(['id','ticket_id','status','assigned_to','user_id','updated_at']);
echo "<pre>";
foreach($tickets as $t) {
    echo "ID: {$t->id} | Ticket: {$t->ticket_id} | Status: {$t->status} | assigned_to: {$t->assigned_to} | user_id: {$t->user_id} | updated: {$t->updated_at}\n";
}

$users = \App\Models\User::all(['id','name','email']);
echo "\n--- USERS ---\n";
foreach($users as $u) {
    $roles = $u->getRoleNames()->implode(', ');
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Roles: {$roles}\n";
}
echo "</pre>";
