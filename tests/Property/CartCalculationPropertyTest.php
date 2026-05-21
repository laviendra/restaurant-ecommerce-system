<?php

namespace Tests\Property;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 6: Cart Calculation Consistency**
 * **Validates: Requirements 3.3, 3.5**
 *
 * For any cart with items, the subtotal of each item should equal price × quantity,
 * and the total should equal the sum of all subtotals.
 */
class CartCalculationPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
    }

    /**
     * Property 6: Cart Calculation Consistency
     *
     * For any cart with items, the subtotal of each item should equal price × quantity,
     * and the total should equal the sum of all subtotals.
     */
    public function testCartItemSubtotalEqualsQuantityTimesPrice(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 100),      // quantity
                Generator\choose(100, 100000)  // price in cents (1.00 to 1000.00)
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
                    'name' => 'Test Product',
                    'price' => $price,
                    'is_available' => true,
                ]);

                $cart = Cart::create(['user_id' => $user->id]);
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);

                // Reload to get fresh data
                $cartItem->refresh();
                $cartItem->load('product');

                // Property: subtotal = price × quantity
                $expectedSubtotal = $price * $quantity;
                $actualSubtotal = $cartItem->getSubtotal();

                $this->assertEquals(
                    round($expectedSubtotal, 2),
                    round($actualSubtotal, 2),
                    "Subtotal should equal price ({$price}) × quantity ({$quantity})"
                );
            });
    }

    /**
     * Property 6: Cart Total Equals Sum of Subtotals
     *
     * For any cart with multiple items, the total should equal the sum of all subtotals.
     */
    public function testCartTotalEqualsSumOfSubtotals(): void
    {
        $this
            ->forAll(
                Generator\seq(Generator\tuple(
                    Generator\choose(1, 10),      // quantity
                    Generator\choose(100, 10000)  // price in cents
                )),
                Generator\choose(1, 5)  // number of items
            )
            ->when(function (array $items, int $count) {
                return count($items) >= $count && $count > 0;
            })
            ->then(function (array $items, int $count) {
                // Limit items to the count
                $items = array_slice($items, 0, $count);
                if (empty($items)) {
                    return;
                }

                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);
                $cart = Cart::create(['user_id' => $user->id]);

                $expectedTotal = 0;

                foreach ($items as $index => [$quantity, $priceInCents]) {
                    $price = $priceInCents / 100;
                    $product = Product::create([
                        'category_id' => $category->id,
                        'name' => 'Test Product ' . $index,
                        'price' => $price,
                        'is_available' => true,
                    ]);

                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                    ]);

                    $expectedTotal += $price * $quantity;
                }

                // Reload cart with items
                $cart->refresh();
                $cart->load('items.product');

                $actualTotal = $cart->getTotal();

                $this->assertEquals(
                    round($expectedTotal, 2),
                    round($actualTotal, 2),
                    "Cart total should equal sum of all item subtotals"
                );
            });
    }
}
