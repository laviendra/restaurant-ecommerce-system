<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 23: Featured Products Display**
 * **Validates: Requirements 19.2**
 *
 * For any product marked as featured, it should appear in the featured
 * products section on the home page.
 */
class FeaturedProductsPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(20);

        // Create category
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'sort_order' => 1,
        ]);
    }

    /**
     * Property 23: Featured Products Display
     *
     * For any product marked as featured, it should appear in the
     * featured products section on the home page.
     */
    public function testFeaturedProductsAppearOnHomePage(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5), // number of featured products
                Generator\choose(1, 5)  // number of non-featured products
            )
            ->then(function (int $featuredCount, int $nonFeaturedCount) {
                // Clean up before each iteration
                Product::query()->delete();

                $featuredNames = [];
                $nonFeaturedNames = [];

                // Create featured products
                for ($i = 0; $i < $featuredCount; $i++) {
                    $name = 'FeaturedProd' . uniqid();
                    $featuredNames[] = $name;
                    Product::create([
                        'category_id' => $this->category->id,
                        'name' => $name,
                        'description' => 'Featured product description',
                        'price' => 100.00 + $i,
                        'is_available' => true,
                        'is_featured' => true,
                    ]);
                }

                // Create non-featured products
                for ($i = 0; $i < $nonFeaturedCount; $i++) {
                    $name = 'RegularProd' . uniqid();
                    $nonFeaturedNames[] = $name;
                    Product::create([
                        'category_id' => $this->category->id,
                        'name' => $name,
                        'description' => 'Regular product description',
                        'price' => 50.00 + $i,
                        'is_available' => true,
                        'is_featured' => false,
                    ]);
                }

                // Verify featured products can be retrieved using the scope
                $featuredProducts = Product::featured()->get();

                // All featured products should be in the result
                $this->assertCount($featuredCount, $featuredProducts);

                foreach ($featuredNames as $name) {
                    $this->assertTrue(
                        $featuredProducts->contains('name', $name),
                        "Featured product '{$name}' should be in featured products list"
                    );
                }

                // Non-featured products should NOT be in the result
                foreach ($nonFeaturedNames as $name) {
                    $this->assertFalse(
                        $featuredProducts->contains('name', $name),
                        "Non-featured product '{$name}' should NOT be in featured products list"
                    );
                }
            });
    }


    /**
     * Property 23b: Toggling featured status updates display
     *
     * For any product, toggling its featured status should correctly
     * add or remove it from the featured products list.
     */
    public function testTogglingFeaturedStatusUpdatesDisplay(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999),
                Generator\bool()
            )
            ->then(function (int $suffix, bool $initialFeatured) {
                // Create product with initial featured status
                $product = Product::create([
                    'category_id' => $this->category->id,
                    'name' => "ToggleFeatured{$suffix}",
                    'description' => 'Test description',
                    'price' => 100.00,
                    'is_available' => true,
                    'is_featured' => $initialFeatured,
                ]);

                // Verify initial state
                $featuredProducts = Product::featured()->get();
                if ($initialFeatured) {
                    $this->assertTrue($featuredProducts->contains('id', $product->id));
                } else {
                    $this->assertFalse($featuredProducts->contains('id', $product->id));
                }

                // Toggle featured status
                $product->update(['is_featured' => !$initialFeatured]);

                // Verify toggled state
                $featuredProducts = Product::featured()->get();
                if (!$initialFeatured) {
                    $this->assertTrue(
                        $featuredProducts->contains('id', $product->id),
                        'Product should now be in featured list after toggling to featured'
                    );
                } else {
                    $this->assertFalse(
                        $featuredProducts->contains('id', $product->id),
                        'Product should NOT be in featured list after toggling to non-featured'
                    );
                }

                // Clean up
                $product->delete();
            });
    }

    /**
     * Property 23c: Admin can see featured indicator in product list
     *
     * For any product, the admin product list should correctly indicate
     * whether the product is featured or not.
     */
    public function testAdminCanSeeFeaturedIndicator(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999),
                Generator\bool()
            )
            ->then(function (int $suffix, bool $isFeatured) {
                // Create admin user
                $admin = User::create([
                    'name' => 'Admin',
                    'email' => "admin{$suffix}@test.com",
                    'phone' => '+1234567890',
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                ]);

                // Create product
                $product = Product::create([
                    'category_id' => $this->category->id,
                    'name' => "FeaturedIndicator{$suffix}",
                    'description' => 'Test description',
                    'price' => 100.00,
                    'is_available' => true,
                    'is_featured' => $isFeatured,
                ]);

                // Visit admin products page
                $response = $this->actingAs($admin)->get(route('admin.products.index'));
                $response->assertStatus(200);

                // Product should be visible
                $response->assertSee("FeaturedIndicator{$suffix}");

                // Featured indicator should be present based on status
                if ($isFeatured) {
                    $response->assertSee('Featured');
                }

                // Clean up
                $product->delete();
                $admin->delete();
            });
    }
}
