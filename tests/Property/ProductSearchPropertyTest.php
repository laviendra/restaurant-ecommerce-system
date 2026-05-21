<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Product;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 4: Product Search Returns Matching Results**
 * **Validates: Requirements 2.4**
 *
 * For any search term, the search results should only contain products
 * whose name contains the search term.
 */
class ProductSearchPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(20);
    }

    /**
     * Property 4: Product Search Returns Matching Results
     *
     * For any search term, the search results should only contain products
     * whose name contains the search term.
     */
    public function testProductSearchReturnsOnlyMatchingProducts(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999) // unique suffix for search term
            )
            ->then(function (int $suffix) {
                // Clean up before each iteration
                Product::query()->delete();
                Category::query()->delete();

                // Create a category
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                    'sort_order' => 1,
                ]);

                // Create a unique search term
                $searchTerm = 'Burger' . $suffix;

                // Create products that should match
                $matchingProduct1 = Product::create([
                    'category_id' => $category->id,
                    'name' => $searchTerm . ' Deluxe',
                    'description' => 'A delicious burger',
                    'price' => 25.00,
                    'is_available' => true,
                ]);

                $matchingProduct2 = Product::create([
                    'category_id' => $category->id,
                    'name' => 'Super ' . $searchTerm,
                    'description' => 'Another burger',
                    'price' => 30.00,
                    'is_available' => true,
                ]);

                // Create products that should NOT match
                $nonMatchingProduct = Product::create([
                    'category_id' => $category->id,
                    'name' => 'Chicken Wings',
                    'description' => 'Crispy chicken',
                    'price' => 15.00,
                    'is_available' => true,
                ]);

                // Perform search
                $response = $this->get(route('products.index', ['search' => $searchTerm]));
                $response->assertStatus(200);

                // Matching products should be visible
                $response->assertSee($matchingProduct1->name);
                $response->assertSee($matchingProduct2->name);

                // Non-matching product should NOT be visible
                $response->assertDontSee($nonMatchingProduct->name);
            });
    }

    /**
     * Property 4: Empty search returns all products
     *
     * When no search term is provided, all products should be displayed.
     */
    public function testEmptySearchReturnsAllProducts(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5) // number of products
            )
            ->then(function (int $productCount) {
                // Clean up before each iteration
                Product::query()->delete();
                Category::query()->delete();

                // Create a category
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                    'sort_order' => 1,
                ]);

                // Create products
                $productNames = [];
                for ($i = 0; $i < $productCount; $i++) {
                    $name = 'Product' . uniqid();
                    $productNames[] = $name;
                    Product::create([
                        'category_id' => $category->id,
                        'name' => $name,
                        'description' => 'Description ' . $i,
                        'price' => 10.00 + $i,
                        'is_available' => true,
                    ]);
                }

                // Perform search with empty term
                $response = $this->get(route('products.index', ['search' => '']));
                $response->assertStatus(200);

                // All products should be visible
                foreach ($productNames as $name) {
                    $response->assertSee($name);
                }
            });
    }
}
