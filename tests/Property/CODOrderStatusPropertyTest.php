<?php

namespace Tests\Property;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 10: COD Order Initial Status**
 * **Validates: Requirements 4.3**
 *
 * For any order created with COD payment method, the order should have
 * payment_status "pending" and order_status "pending".
 */
class CODOrderStatusPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
        $this->orderService = app(OrderService::class);
    }

    /**
     * Property 10: COD Order Initial Status
     *
     * For any order created with COD payment method, the order should have
     * payment_status "pending" and order_status "pending".
     */
    public function testCODOrderHasPendingStatus(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 10),      // quantity
                Generator\choose(100, 10000)  // price in cents
            )
            ->then(function (int $quantity, int $priceInCents) {
                $price = $priceInCents / 100;

                // Create user with cart
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
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);

                // Create COD order
                $order = $this->orderService->createOrder(
                    $user,
                    'Test Address 123',
                    null,
                    'cod'
                );

                // Property: COD order should have payment_status "pending"
                $this->assertEquals(
                    'pending',
                    $order->payment_status,
                    "COD order payment_status should be 'pending'"
                );

                // Property: COD order should have order_status "pending"
                $this->assertEquals(
                    'pending',
                    $order->order_status,
                    "COD order order_status should be 'pending'"
                );

                // Property: Payment method should be 'cod'
                $this->assertEquals(
                    'cod',
                    $order->payment_method,
                    "Order payment_method should be 'cod'"
                );
            });
    }
}
