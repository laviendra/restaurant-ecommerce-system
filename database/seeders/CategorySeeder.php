<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Burgers',
                'slug' => 'burgers',
                'description' => 'Delicious burgers made with 100% beef',
                'sort_order' => 1,
            ],
            [
                'name' => 'Chicken',
                'slug' => 'chicken',
                'description' => 'Crispy and tender chicken items',
                'sort_order' => 2,
            ],
            [
                'name' => 'Sides',
                'slug' => 'sides',
                'description' => 'Perfect sides to complement your meal',
                'sort_order' => 3,
            ],
            [
                'name' => 'Drinks',
                'slug' => 'drinks',
                'description' => 'Refreshing beverages',
                'sort_order' => 4,
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'description' => 'Sweet treats to end your meal',
                'sort_order' => 5,
            ],
            [
                'name' => 'Breakfast',
                'slug' => 'breakfast',
                'description' => 'Start your day with our breakfast menu',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
