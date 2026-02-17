<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Software Development', 'status' => true],
            ['name' => 'Web Design', 'status' => true],
            ['name' => 'Mobile Apps', 'status' => true],
            ['name' => 'Cloud Services', 'status' => true],
            ['name' => 'IT Support', 'status' => true],
            ['name' => 'Consulting', 'status' => true],
        ];

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}

