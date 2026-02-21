<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create initial permissions
        $permissions = [
            'manage users',
            'manage clients',
            'manage products',
            'manage projects',
            'manage services',
            'manage tickets',
            'manage categories',
            'manage designations',
            'manage positions',
            'view dashboard',
            'create tickets',
            'edit tickets',
            'delete tickets',
            'assign tickets',
            'close tickets',
            'view profile',
            'manage roles',
            'manage permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // Create roles and assign default permissions
        $admin = Role::findOrCreate('admin', 'web');
        $admin->syncPermissions(Permission::all());

        $manager = Role::findOrCreate('manager', 'web');
        $manager->syncPermissions([
            'view dashboard',
            'manage tickets',
            'manage clients',
            'manage products',
            'manage projects',
            'manage services',
            'assign tickets',
            'close tickets',
            'view profile',
        ]);

        $staff = Role::findOrCreate('staff', 'web');
        $staff->syncPermissions([
            'view dashboard',
            'manage tickets',
            'edit tickets',
            'close tickets',
            'view profile',
        ]);

        $user = Role::findOrCreate('user', 'web');
        $user->syncPermissions([
            'view dashboard',
            'create tickets',
            'view profile',
        ]);

        // Assign roles to users based on role_id or legacyRole
        $users = User::all();
        foreach ($users as $user) {
            // If they have a legacy role_id, assign role based on that
            if ($user->role_id) {
                $legacyRole = DB::table('legacy_roles')->where('id', $user->role_id)->first();
                if ($legacyRole) {
                    $roleName = strtolower($legacyRole->name);
                    // Ensure role exists before assigning
                    if (Role::where('name', $roleName)->exists()) {
                        $user->assignRole($roleName);
                    }
                }
            }
        }
    }
}
