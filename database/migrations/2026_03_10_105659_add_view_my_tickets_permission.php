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
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view my tickets', 'guard_name' => 'web']);
        
        $userRole = \Spatie\Permission\Models\Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->givePermissionTo($permission);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = \Spatie\Permission\Models\Permission::where('name', 'view my tickets')->first();
        if ($permission) {
            $permission->delete();
        }
    }
};
