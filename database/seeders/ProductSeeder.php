<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $burgers = Category::where('slug', 'burgers')->first();
        $chicken = Category::where('slug', 'chicken')->first();
        $sides = Category::where('slug', 'sides')->first();
        $drinks = Category::where('slug', 'drinks')->first();
        $desserts = Category::where('slug', 'desserts')->first();
        $breakfast = Category::where('slug', 'breakfast')->first();

        // Burgers
        $burgerProducts = [
            [
                'name' => 'Big Mac',
                'description' => 'Dua daging sapi 100% dengan saus Big Mac, selada, keju, acar, bawang di atas roti wijen tiga lapis.',
                'price' => 45000,
                'is_featured' => true,
            ],
            [
                'name' => 'Quarter Pounder with Cheese',
                'description' => 'Seperempat pon daging sapi segar dengan keju, bawang, acar, ketchup, dan mustard.',
                'price' => 52000,
                'is_featured' => true,
            ],
            [
                'name' => 'McDouble',
                'description' => 'Dua daging sapi dengan keju, acar, bawang, ketchup, dan mustard.',
                'price' => 35000,
            ],
            [
                'name' => 'Cheeseburger',
                'description' => 'Daging sapi dengan keju, acar, bawang, ketchup, dan mustard.',
                'price' => 25000,
            ],
            [
                'name' => 'Hamburger',
                'description' => 'Daging sapi dengan acar, bawang, ketchup, dan mustard.',
                'price' => 22000,
            ],
            [
                'name' => 'Double Cheeseburger',
                'description' => 'Dua daging sapi dengan dua keju, acar, bawang, ketchup, dan mustard.',
                'price' => 38000,
            ],
            [
                'name' => 'Beef Deluxe',
                'description' => 'Daging sapi dengan keju, selada, tomat, bawang, dan saus spesial.',
                'price' => 42000,
            ],
            [
                'name' => 'Spicy Beef Burger',
                'description' => 'Daging sapi dengan saus pedas, keju, selada, dan tomat.',
                'price' => 40000,
            ],
        ];

        foreach ($burgerProducts as $product) {
            Product::create(array_merge($product, ['category_id' => $burgers->id]));
        }

        // Chicken
        $chickenProducts = [
            [
                'name' => 'Ayam Goreng McD',
                'description' => 'Ayam goreng renyah dengan bumbu rahasia McDonald\'s.',
                'price' => 28000,
                'is_featured' => true,
            ],
            [
                'name' => 'Chicken McNuggets 6 pcs',
                'description' => 'Enam potong nugget ayam renyah dengan pilihan saus.',
                'price' => 32000,
            ],
            [
                'name' => 'Chicken McNuggets 9 pcs',
                'description' => 'Sembilan potong nugget ayam renyah dengan pilihan saus.',
                'price' => 45000,
            ],
            [
                'name' => 'Chicken McNuggets 20 pcs',
                'description' => 'Dua puluh potong nugget ayam renyah dengan pilihan saus.',
                'price' => 85000,
            ],
            [
                'name' => 'McChicken',
                'description' => 'Ayam crispy dengan mayones dan selada dalam roti lembut.',
                'price' => 35000,
            ],
            [
                'name' => 'Spicy McChicken',
                'description' => 'Ayam crispy pedas dengan mayones dan selada dalam roti lembut.',
                'price' => 37000,
            ],
            [
                'name' => 'Chicken Deluxe',
                'description' => 'Ayam crispy dengan keju, selada, tomat, dan saus spesial.',
                'price' => 42000,
            ],
            [
                'name' => 'Ayam Goreng Pedas',
                'description' => 'Ayam goreng dengan bumbu pedas khas McDonald\'s.',
                'price' => 30000,
            ],
        ];

        foreach ($chickenProducts as $product) {
            Product::create(array_merge($product, ['category_id' => $chicken->id]));
        }

        // Sides
        $sidesProducts = [
            [
                'name' => 'French Fries Small',
                'description' => 'Kentang goreng renyah ukuran kecil.',
                'price' => 15000,
            ],
            [
                'name' => 'French Fries Medium',
                'description' => 'Kentang goreng renyah ukuran sedang.',
                'price' => 18000,
            ],
            [
                'name' => 'French Fries Large',
                'description' => 'Kentang goreng renyah ukuran besar.',
                'price' => 22000,
            ],
            [
                'name' => 'Hash Browns',
                'description' => 'Kentang parut goreng renyah.',
                'price' => 12000,
            ],
            [
                'name' => 'Apple Slices',
                'description' => 'Potongan apel segar sebagai camilan sehat.',
                'price' => 10000,
            ],
            [
                'name' => 'Corn Cup',
                'description' => 'Jagung manis dalam cup.',
                'price' => 8000,
            ],
            [
                'name' => 'Salad Sayur',
                'description' => 'Salad segar dengan sayuran pilihan dan dressing.',
                'price' => 25000,
            ],
        ];

        foreach ($sidesProducts as $product) {
            Product::create(array_merge($product, ['category_id' => $sides->id]));
        }

        // Drinks
        $drinksProducts = [
            [
                'name' => 'Coca Cola Small',
                'description' => 'Minuman berkarbonasi Coca Cola ukuran kecil.',
                'price' => 12000,
            ],
            [
                'name' => 'Coca Cola Medium',
                'description' => 'Minuman berkarbonasi Coca Cola ukuran sedang.',
                'price' => 15000,
            ],
            [
                'name' => 'Coca Cola Large',
                'description' => 'Minuman berkarbonasi Coca Cola ukuran besar.',
                'price' => 18000,
            ],
            [
                'name' => 'Sprite Small',
                'description' => 'Minuman berkarbonasi Sprite ukuran kecil.',
                'price' => 12000,
            ],
            [
                'name' => 'Sprite Medium',
                'description' => 'Minuman berkarbonasi Sprite ukuran sedang.',
                'price' => 15000,
            ],
            [
                'name' => 'Fanta Small',
                'description' => 'Minuman berkarbonasi Fanta ukuran kecil.',
                'price' => 12000,
            ],
            [
                'name' => 'Orange Juice',
                'description' => 'Jus jeruk segar 100% alami.',
                'price' => 18000,
            ],
            [
                'name' => 'Apple Juice',
                'description' => 'Jus apel segar 100% alami.',
                'price' => 18000,
            ],
            [
                'name' => 'Iced Coffee',
                'description' => 'Kopi dingin dengan es.',
                'price' => 20000,
            ],
            [
                'name' => 'Hot Coffee',
                'description' => 'Kopi panas premium.',
                'price' => 18000,
            ],
            [
                'name' => 'Iced Tea',
                'description' => 'Teh dingin dengan es.',
                'price' => 15000,
            ],
            [
                'name' => 'Hot Tea',
                'description' => 'Teh panas.',
                'price' => 12000,
            ],
            [
                'name' => 'Mineral Water',
                'description' => 'Air mineral dalam kemasan.',
                'price' => 8000,
            ],
        ];

        foreach ($drinksProducts as $product) {
            Product::create(array_merge($product, ['category_id' => $drinks->id]));
        }

        // Desserts
        $dessertsProducts = [
            [
                'name' => 'McFlurry Oreo',
                'description' => 'Es krim vanilla dengan topping Oreo yang dihancurkan.',
                'price' => 25000,
                'is_featured' => true,
            ],
            [
                'name' => 'McFlurry M&M',
                'description' => 'Es krim vanilla dengan topping M&M coklat.',
                'price' => 25000,
            ],
            [
                'name' => 'Vanilla Cone',
                'description' => 'Es krim vanilla dalam cone renyah.',
                'price' => 8000,
            ],
            [
                'name' => 'Chocolate Cone',
                'description' => 'Es krim coklat dalam cone renyah.',
                'price' => 8000,
            ],
            [
                'name' => 'Apple Pie',
                'description' => 'Pai apel hangat dengan kulit renyah.',
                'price' => 15000,
            ],
            [
                'name' => 'Chocolate Chip Cookies',
                'description' => 'Kue kering dengan chocolate chip.',
                'price' => 12000,
            ],
            [
                'name' => 'Sundae Strawberry',
                'description' => 'Es krim vanilla dengan saus strawberry.',
                'price' => 18000,
            ],
            [
                'name' => 'Sundae Chocolate',
                'description' => 'Es krim vanilla dengan saus coklat.',
                'price' => 18000,
            ],
        ];

        foreach ($dessertsProducts as $product) {
            Product::create(array_merge($product, ['category_id' => $desserts->id]));
        }

        // Breakfast
        $breakfastProducts = [
            [
                'name' => 'Egg McMuffin',
                'description' => 'Telur, keju, dan ham dalam English muffin.',
                'price' => 32000,
                'is_featured' => true,
            ],
            [
                'name' => 'Sausage McMuffin',
                'description' => 'Sosis dan keju dalam English muffin.',
                'price' => 28000,
            ],
            [
                'name' => 'Sausage McMuffin with Egg',
                'description' => 'Sosis, telur, dan keju dalam English muffin.',
                'price' => 35000,
            ],
            [
                'name' => 'Hotcakes',
                'description' => 'Tiga pancake lembut dengan sirup maple dan mentega.',
                'price' => 30000,
            ],
            [
                'name' => 'Hotcakes with Sausage',
                'description' => 'Tiga pancake dengan sosis dan sirup maple.',
                'price' => 38000,
            ],
            [
                'name' => 'Scrambled Eggs',
                'description' => 'Telur orak-arik lembut.',
                'price' => 15000,
            ],
            [
                'name' => 'Breakfast Burrito',
                'description' => 'Tortilla dengan telur, keju, dan sosis.',
                'price' => 35000,
            ],
            [
                'name' => 'Oatmeal',
                'description' => 'Oatmeal hangat dengan topping buah.',
                'price' => 22000,
            ],
        ];

        foreach ($breakfastProducts as $product) {
            Product::create(array_merge($product, ['category_id' => $breakfast->id]));
        }
    }
}