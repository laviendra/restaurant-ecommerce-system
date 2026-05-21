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
 * **Feature: restaurant-ecommerce, Property 7: Remove from Cart Deletes Item**
 * **Validates: Requirements 3.6**
 *
 * For any item in cart, removing it should result in the item no longer being present in the cart.
 */
class RemoveFromCartPropertyTest extends TestCase
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
     * Property 7: Removing an item deletes it from cart
     *
     * For any item in cart, removing it should result in the item no longer being present.
     */
    public function testRemovingItemDeletesItFromCart(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 100),      // quantity
                Generator\choose(100, 100000)  // price in cents
            )
            ->then(function (int $quantity, int $priceInCents) {
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

                // Create cart with item
                $cart = Cart::create(['user_id' => $user->id]);
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);

                $cartItemId = $cartItem->id;

                // Verify item exists before removal
                $this->assertNotNull(
                    CartItem::find($cartItemId),
                    "Cart item should exist before removal"
                );

                // Remove the item
                $result = $this->cartService->removeItem($cartItem);

                // Property: removal should succeed
                $this->assertTrue($result, "Remove operation should return true");

                // Property: item should no longer exist in database
                $this->assertNull(
                    CartItem::find($cartItemId),
                    "Cart item should not exist after removal"
                );

                // Property: item should not be in cart's items
                $cart->refresh();
                $this->assertFalse(
                    $cart->items()->where('id', $cartItemId)->exists(),
                    "Cart should not contain the removed item"
                );
            });
    }

    /**
     * Property 7: Removing item from cart with multiple items only removes that item
     *
     * For any cart with multiple items, removing one item should only remove that specific item.
     */
    public function testRemovingItemOnlyRemovesThatItem(): void
    {
        $this
            ->forAll(
                Generator\choose(2, 5)  // number of items in cart
            )
            ->then(function (int $itemCount) {
                // Create necessary models
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);
                $cart = Cart::create(['user_id' => $user->id]);

                $cartItems = [];
                for ($i = 0; $i < $itemCount; $i++) {
                    $product = Product::create([
                        'category_id' => $category->id,
                        'name' => 'Test Product ' . $i . '-' . uniqid(),
                        'price' => 10.00 + $i,
                        'is_available' => true,
                    ]);

                    $cartItems[] = CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => 1,
                    ]);
                }

                // Remove the first item
                $itemToRemove = $cartItems[0];
                $itemToRemoveId = $itemToRemove->id;
                $this->cartService->removeItem($itemToRemove);

                // Property: removed item should not exist
                $this->assertNull(
                    CartItem::find($itemToRemoveId),
                    "Removed item should not exist"
                );

                // Property: other items should still exist
                for ($i = 1; $i < $itemCount; $i++) {
                    $this->assertNotNull(
                        CartItem::find($cartItems[$i]->id),
                        "Other cart items should still exist"
                    );
                }

                // Property: cart should have one less item
                $cart->refresh();
                $this->assertEquals(
                    $itemCount - 1,
                    $cart->items()->count(),
                    "Cart should have one less item after removal"
                );
            });
    }

    /**
     * Property 7: Setting quantity to zero removes item
     *
     * For any item in cart, updating quantity to zero should remove the item.
     */
    public function testSettingQuantityToZeroRemovesItem(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 100)  // initial quantity
            )
            ->then(function (int $initialQuantity) {
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

                // Create cart with item
                $cart = Cart::create(['user_id' => $user->id]);
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $initialQuantity,
                ]);

                $cartItemId = $cartItem->id;

                // Update quantity to zero
                $result = $this->cartService->updateQuantity($cartItem, 0);

                // Property: result should be null (item removed)
                $this->assertNull($result, "Updating quantity to 0 should return null");

                // Property: item should no longer exist
                $this->assertNull(
                    CartItem::find($cartItemId),
                    "Cart item should not exist after setting quantity to 0"
                );
            });
    }
}
