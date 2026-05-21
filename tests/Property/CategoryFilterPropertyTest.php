<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Product;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 21: Category Filter Returns Correct Products**
 * **Validates: Requirements 11.2**
 *
 * For any category filter applied, the product list should only contain
 * products belonging to that category.
 */
class CategoryFilterPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(20);
    }

    /**
     * Property 21: Category Filter Returns Correct Products
     *
     * For any category filter applied, the product list should only contain
     * products belonging to that category.
     */
    public function testCategoryFilterReturnsOnlyProductsInCategory(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999) // unique suffix
            )
            ->then(function (int $suffix) {
                // Clean up before each iteration
                Product::query()->delete();
                Category::query()->delete();

                // Create two categories
                $category1 = Category::create([
                    'name' => 'Burgers',
                    'slug' => 'burgers-' . $suffix,
                    'sort_order' => 1,
                ]);

                $category2 = Category::create([
                    'name' => 'Drinks',
                    'slug' => 'drinks-' . $suffix,
                    'sort_order' => 2,
                ]);

                // Create products in category 1
                $burgerProduct1 = Product::create([
                    'category_id' => $category1->id,
                    'name' => 'Big Mac ' . $suffix,
                    'description' => 'Classic burger',
                    'price' => 35.00,
                    'is_available' => true,
                ]);

                $burgerProduct2 = Product::create([
                    'category_id' => $category1->id,
                    'name' => 'Quarter Pounder ' . $suffix,
                    'description' => 'Beef burger',
                    'price' => 40.00,
                    'is_available' => true,
                ]);

                // Create products in category 2
                $drinkProduct = Product::create([
                    'category_id' => $category2->id,
                    'name' => 'Coca Cola ' . $suffix,
                    'description' => 'Refreshing drink',
                    'price' => 15.00,
                    'is_available' => true,
                ]);

                // Filter by category 1 (Burgers)
                $response = $this->get(route('products.index', ['category' => $category1->id]));
                $response->assertStatus(200);

                // Products from category 1 should be visible
                $response->assertSee($burgerProduct1->name);
                $response->assertSee($burgerProduct2->name);

                // Products from category 2 should NOT be visible
                $response->assertDontSee($drinkProduct->name);
            });
    }

    /**
     * Property 21: No category filter returns all products
     *
     * When no category filter is applied, all products should be displayed.
     */
    public function testNoCategoryFilterReturnsAllProducts(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999) // unique suffix
            )
            ->then(function (int $suffix) {
                // Clean up before each iteration
                Product::query()->delete();
                Category::query()->delete();

                // Create two categories
                $category1 = Category::create([
                    'name' => 'Burgers',
                    'slug' => 'burgers-' . $suffix,
                    'sort_order' => 1,
                ]);

                $category2 = Category::create([
                    'name' => 'Drinks',
                    'slug' => 'drinks-' . $suffix,
                    'sort_order' => 2,
                ]);

                // Create products in different categories
                $product1 = Product::create([
                    'category_id' => $category1->id,
                    'name' => 'Burger ' . $suffix,
                    'description' => 'A burger',
                    'price' => 35.00,
                    'is_available' => true,
                ]);

                $product2 = Product::create([
                    'category_id' => $category2->id,
                    'name' => 'Drink ' . $suffix,
                    'description' => 'A drink',
                    'price' => 15.00,
                    'is_available' => true,
                ]);

                // No category filter
                $response = $this->get(route('products.index'));
                $response->assertStatus(200);

                // All products should be visible
                $response->assertSee($product1->name);
                $response->assertSee($product2->name);
            });
    }
}
