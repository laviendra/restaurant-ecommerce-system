<?php

namespace Tests\Property;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 8: Checkout Total Matches Cart**
 * **Validates: Requirements 4.1**
 *
 * For any cart proceeding to checkout, the checkout total amount should equal the cart total.
 */
class CheckoutTotalPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected CartService $cartService;
    protected OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
        $this->cartService = app(CartService::class);
        $this->orderService = app(OrderService::class);
    }

    /**
     * Property 8: Checkout Total Matches Cart
     *
     * For any cart proceeding to checkout, the checkout total amount should equal the cart total.
     */
    public function testCheckoutTotalMatchesCartTotal(): void
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
                }

                // Get cart total before checkout
                $cart->refresh();
                $cart->load('items.product');
                $cartTotal = $cart->getTotal();

                // Create order through OrderService
                $order = $this->orderService->createOrder(
                    $user,
                    'Test Address 123',
                    null,
                    'cod'
                );

                // Property: Order total should match cart total
                $this->assertEquals(
                    round($cartTotal, 2),
                    round((float) $order->total_amount, 2),
                    "Order total ({$order->total_amount}) should equal cart total ({$cartTotal})"
                );
            });
    }
}
