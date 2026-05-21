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
 * **Feature: restaurant-ecommerce, Property 9: Order Preserves Delivery Information**
 * **Validates: Requirements 4.2**
 *
 * For any checkout submission with delivery address and notes, the created order
 * should contain the exact same delivery information.
 */
class DeliveryInfoPropertyTest extends TestCase
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
     * Property 9: Order Preserves Delivery Information
     *
     * For any checkout submission with delivery address and notes, the created order
     * should contain the exact same delivery information.
     */
    public function testOrderPreservesDeliveryInformation(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    function ($s) { return strlen(trim($s)) > 0 && strlen($s) <= 500; },
                    Generator\string()
                ),
                Generator\oneOf(
                    Generator\constant(null),
                    Generator\suchThat(
                        function ($s) { return strlen($s) <= 1000; },
                        Generator\string()
                    )
                )
            )
            ->then(function (string $deliveryAddress, ?string $notes) {
                // Create user with cart
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => 'Test Product',
                    'price' => 10.00,
                    'is_available' => true,
                ]);
                $cart = Cart::create(['user_id' => $user->id]);
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);

                // Create order with delivery info
                $order = $this->orderService->createOrder(
                    $user,
                    $deliveryAddress,
                    $notes,
                    'cod'
                );

                // Property: Order should preserve exact delivery address
                $this->assertEquals(
                    $deliveryAddress,
                    $order->delivery_address,
                    "Order delivery address should match input"
                );

                // Property: Order should preserve exact notes
                $this->assertEquals(
                    $notes,
                    $order->notes,
                    "Order notes should match input"
                );
            });
    }
}
