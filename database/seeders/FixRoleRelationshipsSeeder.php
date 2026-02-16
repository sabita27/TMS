<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class FixRoleRelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fix Admin
        $admin = User::where('email', 'admin@tms.com')->first();
        if ($admin) {
            $adminRole = Role::where('name', 'admin')->first();
            $admin->update(['role_id' => $adminRole->id]);
        }

        // Fix Manager
        $manager = User::where('email', 'manager@tms.com')->first();
        if ($manager) {
            $managerRole = Role::where('name', 'manager')->first();
            $manager->update(['role_id' => $managerRole->id]);
        }
        
        // Fix Staff
        $staff = User::where('email', 'staff@tms.com')->first();
        if ($staff) {
            $staffRole = Role::where('name', 'staff')->first();
            $staff->update(['role_id' => $staffRole->id]);
        }

        // Fix User
        $user = User::where('email', 'user@tms.com')->first();
        if ($user) {
            $userRole = Role::where('name', 'user')->first();
            $user->update(['role_id' => $userRole->id]);
        }
    }
}
