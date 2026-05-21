<?php

namespace Tests\Property;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 5: Add to Cart Increases Quantity**
 * **Validates: Requirements 3.1, 3.2**
 *
 * For any available product, adding it to cart should either create a new cart item
 * with quantity 1, or increment existing item quantity by 1.
 */
class AddToCartPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService();
        $this->limitTo(100);
    }

    /**
     * Property 5: Adding a new product to cart creates item with quantity 1
     *
     * For any available product not in cart, adding it should create a new cart item
     * with quantity of 1.
     */
    public function testAddingNewProductCreatesItemWithQuantityOne(): void
    {
        $this
            ->forAll(
                Generator\choose(100, 100000)  // price in cents (1.00 to 1000.00)
            )
            ->then(function (int $priceInCents) {
                $price = $priceInCents / 100;

                // Create necessary models
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => 'Test Product ' . uniqid(),
                    'price' => $price,
                    'is_available' => true,
                ]);

                // Add product to cart
                $cartItem = $this->cartService->addItem($product, null, $user);

                // Property: new item should have quantity of 1
                $this->assertEquals(
                    1,
                    $cartItem->quantity,
                    "New cart item should have quantity of 1"
                );

                // Property: cart item should reference the correct product
                $this->assertEquals(
                    $product->id,
                    $cartItem->product_id,
                    "Cart item should reference the added product"
                );
            });
    }

    /**
     * Property 5: Adding an existing product increments quantity by 1
     *
     * For any product already in cart, adding it again should increment
     * the quantity by 1.
     */
    public function testAddingExistingProductIncrementsQuantityByOne(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 50),       // initial quantity
                Generator\choose(100, 100000)  // price in cents
            )
            ->then(function (int $initialQuantity, int $priceInCents) {
                $price = $priceInCents / 100;

                // Create necessary models
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => 'Test Product ' . uniqid(),
                    'price' => $price,
                    'is_available' => true,
                ]);

                // Create cart with existing item
                $cart = Cart::create(['user_id' => $user->id]);
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $initialQuantity,
                ]);

                // Add the same product again
                $cartItem = $this->cartService->addItem($product, null, $user);

                // Property: quantity should be incremented by 1
                $expectedQuantity = $initialQuantity + 1;
                $this->assertEquals(
                    $expectedQuantity,
                    $cartItem->quantity,
                    "Adding existing product should increment quantity from {$initialQuantity} to {$expectedQuantity}"
                );
            });
    }

    /**
     * Property 5: Adding product with notes preserves notes
     *
     * For any product added with notes, the cart item should preserve those notes.
     */
    public function testAddingProductWithNotesPreservesNotes(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    function ($s) { return strlen($s) > 0 && strlen($s) <= 500; },
                    Generator\string()
                )
            )
            ->then(function (string $notes) {
                // Create necessary models
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => 'Test Product ' . uniqid(),
                    'price' => 10.00,
                    'is_available' => true,
                ]);

                // Add product with notes
                $cartItem = $this->cartService->addItem($product, $notes, $user);

                // Property: notes should be preserved
                $this->assertEquals(
                    $notes,
                    $cartItem->notes,
                    "Cart item notes should match the provided notes"
                );
            });
    }
}
