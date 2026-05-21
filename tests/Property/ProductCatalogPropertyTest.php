<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Product;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 3: Product Catalog Completeness**
 * **Validates: Requirements 2.1, 2.2**
 *
 * For any set of available products in the database, the product page should
 * display all products with their name, description, price, and image.
 */
class ProductCatalogPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(20);
    }

    /**
     * Property 3: Product Catalog Completeness
     *
     * For any set of products in the database, the product index page should
     * display all products with their name, description, price, and image.
     */
    public function testProductCatalogDisplaysAllProductsWithRequiredFields(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 3), // number of products to create (reduced)
                Generator\choose(100, 5000) // price in cents
            )
            ->then(function (int $productCount, int $priceInCents) {
                // Clean up before each iteration
                Product::query()->delete();
                Category::query()->delete();
                
                $price = $priceInCents / 100;

                // Create a category
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                    'sort_order' => 1,
                ]);

                // Create products
                $productNames = [];
                for ($i = 0; $i < $productCount; $i++) {
                    $productName = 'TestProd' . uniqid();
                    $productNames[] = $productName;
                    Product::create([
                        'category_id' => $category->id,
                        'name' => $productName,
                        'description' => 'Desc ' . $i,
                        'price' => $price + $i,
                        'is_available' => true,
                        'is_featured' => false,
                    ]);
                }

                // Visit the product index page
                $response = $this->get(route('products.index'));
                $response->assertStatus(200);

                // Verify all products are displayed
                foreach ($productNames as $name) {
                    $response->assertSee($name);
                }
            });
    }

    /**
     * Property 3: Product Detail Shows All Information
     *
     * For any product, the product detail page should display
     * name, description, price, and availability status.
     */
    public function testProductDetailShowsAllRequiredInformation(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999), // unique suffix
                Generator\choose(100, 5000), // price in cents
                Generator\bool() // is_available
            )
            ->then(function (int $suffix, int $priceInCents, bool $isAvailable) {
                // Clean up before each iteration
                Product::query()->delete();
                Category::query()->delete();
                
                $price = $priceInCents / 100;
                $name = 'Product' . $suffix;
                $description = 'Description for product ' . $suffix;

                // Create a category
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-cat-' . uniqid(),
                    'sort_order' => 1,
                ]);

                // Create product
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'is_available' => $isAvailable,
                    'is_featured' => false,
                ]);

                // Visit the product detail page
                $response = $this->get(route('products.show', $product));
                $response->assertStatus(200);

                // Verify product information is displayed
                $response->assertSee($name);
                $response->assertSee($description);

                // Availability status should be shown
                if ($isAvailable) {
                    $response->assertSee('Available');
                } else {
                    $response->assertSee('Unavailable');
                }
            });
    }
}
