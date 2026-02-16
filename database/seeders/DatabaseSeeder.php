<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use App\Models\Product;
use App\Models\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tms.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Manager
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@tms.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        // Create Staff
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@tms.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create Default User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@tms.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Categories & Subcategories
        $cat1 = ProductCategory::create(['name' => 'Software']);
        $cat2 = ProductCategory::create(['name' => 'Hardware']);

        ProductSubCategory::create(['category_id' => $cat1->id, 'name' => 'Operating System']);
        ProductSubCategory::create(['category_id' => $cat1->id, 'name' => 'Office Suite']);
        
        $sub3 = ProductSubCategory::create(['category_id' => $cat2->id, 'name' => 'Laptops']);
        ProductSubCategory::create(['category_id' => $cat2->id, 'name' => 'Printers']);

        // Products
        Product::create([
            'category_id' => $cat2->id,
            'sub_category_id' => $sub3->id,
            'name' => 'MacBook Pro M2',
            'price' => 1999.00,
            'description' => 'High performance laptop for professionals.'
        ]);

        // Designations
        Designation::create(['name' => 'Senior Support Engineer']);
        Designation::create(['name' => 'Junior Technician']);
    }
}
