<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change status column from ENUM to VARCHAR to support dynamic statuses
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status VARCHAR(100) NOT NULL DEFAULT 'open'");
    }

    public function down(): void
    {
        // Revert back to enum (only safe if all values match enum options)
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('open','in-progress','resolved','closed') DEFAULT 'open'");
    }
};
