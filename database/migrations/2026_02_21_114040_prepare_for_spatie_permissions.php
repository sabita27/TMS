<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('roles')) {
            Schema::rename('roles', 'legacy_roles');
        }
        
        // Drop other priority tables if they exist from failed attempts
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_permissions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('legacy_roles')) {
            Schema::rename('legacy_roles', 'roles');
        }
    }
};
