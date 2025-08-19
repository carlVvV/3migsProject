<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Wedding Gowns',
                'slug' => 'wedding-gowns',
                'description' => 'Elegant wedding gowns for your special day',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'name' => 'Barong Tagalog',
                'slug' => 'barong-tagalog',
                'description' => 'Traditional Filipino formal wear for men',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'name' => 'Evening Gowns',
                'slug' => 'evening-gowns',
                'description' => 'Stunning evening gowns for formal events',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Jewelry, veils, and other accessories',
                'status' => 'active',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
