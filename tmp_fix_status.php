<?php
// Run this once: http://localhost/TMS/tmp_fix_status.php
// This fixes the tickets.status ENUM to VARCHAR and verifies assignment

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<pre style='font-family:monospace; font-size:14px; padding:20px;'>";

try {
    // 1. Change status column from ENUM to VARCHAR
    echo "Step 1: Changing tickets.status from ENUM to VARCHAR...\n";
    DB::statement("ALTER TABLE tickets MODIFY COLUMN status VARCHAR(100) NOT NULL DEFAULT 'open'");
    echo "  ✓ Done!\n\n";

    // 2. Show current ticket data
    echo "Step 2: Current tickets in database:\n";
    $tickets = DB::table('tickets')->get(['id', 'ticket_id', 'status', 'assigned_to', 'user_id']);
    foreach ($tickets as $t) {
        echo "  ID: {$t->id} | Ticket: {$t->ticket_id} | Status: '{$t->status}' | assigned_to: {$t->assigned_to} | user_id: {$t->user_id}\n";
    }
    echo "\n";

    // 3. Show users with staff role
    echo "Step 3: Staff users:\n";
    $staffUsers = \App\Models\User::role('staff')->get(['id', 'name', 'email']);
    foreach ($staffUsers as $u) {
        echo "  ID: {$u->id} | Name: {$u->name} | Email: {$u->email}\n";
        $assigned = DB::table('tickets')->where('assigned_to', $u->id)->count();
        echo "    → Tickets assigned to this user: {$assigned}\n";
    }
    echo "\n";

    echo "✅ ALL DONE! Now refresh the staff dashboard to see assigned tickets.\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
