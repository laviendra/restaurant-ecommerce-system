<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Test Admin Product Management (CRUD) functionality
 * Requirements: 2.1, 2.2, 2.3, 2.4, 2.5
 */
class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        
        // Disable CSRF for testing
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular user
        $this->regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'phone' => '+1234567891',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create category
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'sort_order' => 1,
        ]);
    }

    /**
     * Test product list page displays correctly
     * Requirements: 2.1
     */
    public function testProductListPageDisplaysCorrectly(): void
    {
        // Create some test products
        $product1 = Product::create([
            'name' => 'Test Product 1',
            'description' => 'Description 1',
            'price' => 10.99,
            'category_id' => $this->category->id,
            'image' => 'products/test1.jpg',
            'is_available' => true,
            'is_featured' => false,
        ]);

        $product2 = Product::create([
            'name' => 'Test Product 2',
            'description' => 'Description 2',
            'price' => 15.99,
            'category_id' => $this->category->id,
            'image' => 'products/test2.jpg',
            'is_available' => false,
            'is_featured' => true,
        ]);

        // Access product list as admin
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Product Management');
        $response->assertSee('Test Product 1');
        $response->assertSee('Test Product 2');
        $response->assertSee('Rp 11');
        $response->assertSee('Rp 16');
        $response->assertSee('Add New Product');
        
        // Check for action buttons
        $response->assertSee('Edit');
        $response->assertSee('Delete');
    }

    /**
     * Test create new product functionality
     * Requirements: 2.2
     */
    public function testCreateNewProductFunctionality(): void
    {
        // Access create product page
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertSee('Add New Product');
        $response->assertSee('Product Name');
        $response->assertSee('Description');
        $response->assertSee('Price');
        $response->assertSee('Category');

        // Create a new product
        $productData = [
            'name' => 'New Test Product',
            'description' => 'This is a new test product',
            'price' => 25.99,
            'category_id' => $this->category->id,
            'image' => UploadedFile::fake()->image('product.jpg'),
            'is_available' => true,
            'is_featured' => false,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), $productData);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product created successfully.');

        // Verify product was created in database
        $this->assertDatabaseHas('products', [
            'name' => 'New Test Product',
            'description' => 'This is a new test product',
            'price' => 25.99,
            'category_id' => $this->category->id,
            'is_available' => true,
            'is_featured' => false,
        ]);

        // Verify image was stored
        $product = Product::where('name', 'New Test Product')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    /**
     * Test edit existing product functionality
     * Requirements: 2.3
     */
    public function testEditExistingProductFunctionality(): void
    {
        // Create a product to edit
        $product = Product::create([
            'name' => 'Original Product',
            'description' => 'Original description',
            'price' => 20.00,
            'category_id' => $this->category->id,
            'image' => 'products/original.jpg',
            'is_available' => true,
            'is_featured' => false,
        ]);

        // Access edit product page
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertSee('Edit Product');
        $response->assertSee('Original Product');
        $response->assertSee('Original description');
        $response->assertSee('20');

        // Update the product
        $updateData = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 30.00,
            'category_id' => $this->category->id,
            'is_available' => false,
            'is_featured' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.products.update', $product), $updateData);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product updated successfully.');

        // Verify product was updated in database
        $product->refresh();
        $this->assertEquals('Updated Product', $product->name);
        $this->assertEquals('Updated description', $product->description);
        $this->assertEquals(30.00, $product->price);
        $this->assertFalse($product->is_available);
        $this->assertTrue($product->is_featured);
    }

    /**
     * Test delete product functionality
     * Requirements: 2.4
     */
    public function testDeleteProductFunctionality(): void
    {
        // Create a product to delete
        $product = Product::create([
            'name' => 'Product to Delete',
            'description' => 'This will be deleted',
            'price' => 15.00,
            'category_id' => $this->category->id,
            'image' => 'products/delete-me.jpg',
            'is_available' => true,
            'is_featured' => false,
        ]);

        $productId = $product->id;

        // Delete the product
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product deleted successfully.');

        // Verify product was deleted from database
        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }

    /**
     * Test toggle product availability status
     * Requirements: 2.5
     */
    public function testToggleProductAvailabilityStatus(): void
    {
        // Create a product that is available
        $product = Product::create([
            'name' => 'Toggle Availability Product',
            'description' => 'Test availability toggle',
            'price' => 12.00,
            'category_id' => $this->category->id,
            'image' => 'products/toggle.jpg',
            'is_available' => true,
            'is_featured' => false,
        ]);

        // Toggle availability to unavailable
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.toggle-availability', $product));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Product marked as unavailable.');

        // Verify availability was toggled
        $product->refresh();
        $this->assertFalse($product->is_available);

        // Toggle back to available
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.toggle-availability', $product));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Product marked as available.');

        // Verify availability was toggled back
        $product->refresh();
        $this->assertTrue($product->is_available);
    }

    /**
     * Test toggle product featured status
     * Requirements: 2.5
     */
    public function testToggleProductFeaturedStatus(): void
    {
        // Create a product that is not featured
        $product = Product::create([
            'name' => 'Toggle Featured Product',
            'description' => 'Test featured toggle',
            'price' => 18.00,
            'category_id' => $this->category->id,
            'image' => 'products/featured.jpg',
            'is_available' => true,
            'is_featured' => false,
        ]);

        // Toggle featured to true
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.toggle-featured', $product));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Product marked as featured.');

        // Verify featured status was toggled
        $product->refresh();
        $this->assertTrue($product->is_featured);

        // Toggle back to not featured
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.toggle-featured', $product));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Product marked as not featured.');

        // Verify featured status was toggled back
        $product->refresh();
        $this->assertFalse($product->is_featured);
    }

    /**
     * Test non-admin users cannot access admin product management
     */
    public function testNonAdminCannotAccessProductManagement(): void
    {
        // Test product list access
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.products.index'));
        $response->assertStatus(403);

        // Test create product access
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.products.create'));
        $response->assertStatus(403);

        // Create a product for testing edit/delete access
        $product = Product::create([
            'name' => 'Test Product',
            'description' => 'Test',
            'price' => 10.00,
            'category_id' => $this->category->id,
            'is_available' => true,
            'is_featured' => false,
        ]);

        // Test edit product access
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.products.edit', $product));
        $response->assertStatus(403);

        // Test delete product access
        $response = $this->actingAs($this->regularUser)
            ->delete(route('admin.products.destroy', $product));
        $response->assertStatus(403);
    }

    /**
     * Test product creation validation
     */
    public function testProductCreationValidation(): void
    {
        // Test with missing required fields
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['name', 'price', 'category_id', 'image']);

        // Test with invalid price
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Product',
                'price' => -5.00,
                'category_id' => $this->category->id,
                'image' => UploadedFile::fake()->image('product.jpg'),
            ]);

        $response->assertSessionHasErrors(['price']);

        // Test with invalid category
        $response = $this->actingAs($this->admin)
            ->post(route('admin.products.store'), [
                'name' => 'Test Product',
                'price' => 10.00,
                'category_id' => 999999, // Non-existent category
                'image' => UploadedFile::fake()->image('product.jpg'),
            ]);

        $response->assertSessionHasErrors(['category_id']);
    }

    /**
     * Test product filtering functionality
     */
    public function testProductFilteringFunctionality(): void
    {
        // Create products with different statuses
        $availableProduct = Product::create([
            'name' => 'Available Product',
            'price' => 10.00,
            'category_id' => $this->category->id,
            'is_available' => true,
            'is_featured' => false,
        ]);

        $unavailableProduct = Product::create([
            'name' => 'Unavailable Product',
            'price' => 15.00,
            'category_id' => $this->category->id,
            'is_available' => false,
            'is_featured' => false,
        ]);

        $featuredProduct = Product::create([
            'name' => 'Featured Product',
            'price' => 20.00,
            'category_id' => $this->category->id,
            'is_available' => true,
            'is_featured' => true,
        ]);

        // Test filter by availability
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['availability' => 'available']));

        $response->assertStatus(200);
        $response->assertSee('Available Product');
        $response->assertSee('Featured Product');
        $response->assertDontSee('Unavailable Product');

        // Test filter by featured
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['featured' => 'yes']));

        $response->assertStatus(200);
        $response->assertSee('Featured Product');
        $response->assertDontSee('Available Product');
        $response->assertDontSee('Unavailable Product');

        // Test search by name
        $response = $this->actingAs($this->admin)
            ->get(route('admin.products.index', ['search' => 'Available']));

        $response->assertStatus(200);
        $response->assertSee('Available Product');
        $response->assertDontSee('Featured Product');
    }
}