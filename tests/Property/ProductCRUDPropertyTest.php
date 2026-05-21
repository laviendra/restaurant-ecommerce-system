<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 15: Product CRUD Operations**
 * **Validates: Requirements 6.2, 6.3, 6.4, 6.5**
 *
 * For any product data, creating a product should add it to catalog,
 * editing should update it, toggling availability should change status,
 * and deleting should remove it from catalog.
 */
class ProductCRUDPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->limitTo(20);

        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create category
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'sort_order' => 1,
        ]);
    }


    /**
     * Property 15a: Creating a product adds it to the catalog
     * **Validates: Requirements 6.2**
     */
    public function testCreatingProductAddsItToCatalog(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999),
                Generator\choose(1000, 100000)
            )
            ->then(function (int $suffix, int $priceInCents) {
                $productName = "NewProduct{$suffix}";
                $price = $priceInCents / 100;

                $initialCount = Product::count();

                // Create product as admin
                $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
                    'name' => $productName,
                    'description' => 'Test description',
                    'price' => $price,
                    'category_id' => $this->category->id,
                    'image' => UploadedFile::fake()->image('product.jpg'),
                    'is_available' => true,
                    'is_featured' => false,
                ]);

                $response->assertRedirect(route('admin.products.index'));

                // Product should be in database
                $this->assertDatabaseHas('products', [
                    'name' => $productName,
                    'price' => $price,
                    'category_id' => $this->category->id,
                ]);

                // Count should increase by 1
                $this->assertEquals($initialCount + 1, Product::count());
            });
    }

    /**
     * Property 15b: Editing a product updates it in the catalog
     * **Validates: Requirements 6.3**
     */
    public function testEditingProductUpdatesItInCatalog(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999),
                Generator\choose(1000, 50000),
                Generator\choose(50001, 100000)
            )
            ->then(function (int $suffix, int $originalPriceInCents, int $newPriceInCents) {
                $originalName = "OriginalProduct{$suffix}";
                $newName = "UpdatedProduct{$suffix}";
                $originalPrice = $originalPriceInCents / 100;
                $newPrice = $newPriceInCents / 100;

                // Create product first
                $product = Product::create([
                    'name' => $originalName,
                    'description' => 'Original description',
                    'price' => $originalPrice,
                    'category_id' => $this->category->id,
                    'is_available' => true,
                    'is_featured' => false,
                ]);

                // Update product as admin
                $response = $this->actingAs($this->admin)->put(route('admin.products.update', $product), [
                    'name' => $newName,
                    'description' => 'Updated description',
                    'price' => $newPrice,
                    'category_id' => $this->category->id,
                    'is_available' => true,
                    'is_featured' => false,
                ]);

                $response->assertRedirect(route('admin.products.index'));

                // Product should be updated in database
                $product->refresh();
                $this->assertEquals($newName, $product->name);
                $this->assertEquals($newPrice, $product->price);
                $this->assertEquals('Updated description', $product->description);
            });
    }


    /**
     * Property 15c: Toggling availability changes the product status
     * **Validates: Requirements 6.4**
     */
    public function testTogglingAvailabilityChangesStatus(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999),
                Generator\bool()
            )
            ->then(function (int $suffix, bool $initialAvailability) {
                // Create product with initial availability
                $product = Product::create([
                    'name' => "ToggleProduct{$suffix}",
                    'description' => 'Test description',
                    'price' => 100.00,
                    'category_id' => $this->category->id,
                    'is_available' => $initialAvailability,
                    'is_featured' => false,
                ]);

                // Toggle availability as admin
                $response = $this->actingAs($this->admin)->post(
                    route('admin.products.toggle-availability', $product)
                );

                $response->assertRedirect();

                // Availability should be toggled
                $product->refresh();
                $this->assertEquals(!$initialAvailability, $product->is_available);
            });
    }

    /**
     * Property 15d: Deleting a product removes it from the catalog
     * **Validates: Requirements 6.5**
     */
    public function testDeletingProductRemovesItFromCatalog(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999)
            )
            ->then(function (int $suffix) {
                // Create product
                $product = Product::create([
                    'name' => "DeleteProduct{$suffix}",
                    'description' => 'Test description',
                    'price' => 100.00,
                    'category_id' => $this->category->id,
                    'is_available' => true,
                    'is_featured' => false,
                ]);

                $productId = $product->id;
                $initialCount = Product::count();

                // Delete product as admin
                $response = $this->actingAs($this->admin)->delete(
                    route('admin.products.destroy', $product)
                );

                $response->assertRedirect(route('admin.products.index'));

                // Product should be removed from database
                $this->assertDatabaseMissing('products', ['id' => $productId]);

                // Count should decrease by 1
                $this->assertEquals($initialCount - 1, Product::count());
            });
    }
}
