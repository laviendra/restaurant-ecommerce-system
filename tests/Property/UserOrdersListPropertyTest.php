<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\OrderService;
use App\Services\CartService;
use App\Services\InvoiceService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 13: User Orders List Completeness**
 * **Validates: Requirements 5.1**
 *
 * For any user, the orders list should display all orders belonging to that user.
 */
class UserOrdersListPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected OrderService $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = app(OrderService::class);
        $this->limitTo(100);
    }

    /**
     * Property 13: User Orders List Completeness
     *
     * For any user, the orders list should display all orders belonging to that user.
     */
    public function testUserOrdersListContainsAllUserOrders(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 10)  // number of orders to create for the user
            )
            ->then(function (int $orderCount) {
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);

                $createdOrderIds = [];

                // Create orders for the user
                for ($i = 0; $i < $orderCount; $i++) {
                    $order = Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $user->id,
                        'total_amount' => rand(10000, 100000) / 100,
                        'delivery_address' => 'Test Address ' . $i,
                        'payment_method' => $i % 2 === 0 ? 'cod' : 'transfer_bank',
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);
                    $createdOrderIds[] = $order->id;
                }

                // Get orders using the service
                $orders = $this->orderService->getOrdersByUser($user);

                // Property: All created orders should be in the list
                $retrievedOrderIds = $orders->pluck('id')->toArray();

                foreach ($createdOrderIds as $orderId) {
                    $this->assertContains(
                        $orderId,
                        $retrievedOrderIds,
                        "Order {$orderId} should be in the user's orders list"
                    );
                }

                // Property: Count should match
                $this->assertCount(
                    $orderCount,
                    $orders,
                    "User should have exactly {$orderCount} orders"
                );
            });
    }

    /**
     * Property 13: User Orders List Does Not Contain Other Users' Orders
     *
     * For any user, the orders list should NOT contain orders from other users.
     */
    public function testUserOrdersListDoesNotContainOtherUsersOrders(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5),  // orders for user 1
                Generator\choose(1, 5)   // orders for user 2
            )
            ->then(function (int $user1OrderCount, int $user2OrderCount) {
                $user1 = User::factory()->create();
                $user2 = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);

                $user1OrderIds = [];
                $user2OrderIds = [];

                // Create orders for user 1
                for ($i = 0; $i < $user1OrderCount; $i++) {
                    $order = Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-U1-' . strtoupper(uniqid()),
                        'user_id' => $user1->id,
                        'total_amount' => rand(10000, 100000) / 100,
                        'delivery_address' => 'User 1 Address ' . $i,
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);
                    $user1OrderIds[] = $order->id;
                }

                // Create orders for user 2
                for ($i = 0; $i < $user2OrderCount; $i++) {
                    $order = Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-U2-' . strtoupper(uniqid()),
                        'user_id' => $user2->id,
                        'total_amount' => rand(10000, 100000) / 100,
                        'delivery_address' => 'User 2 Address ' . $i,
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);
                    $user2OrderIds[] = $order->id;
                }

                // Get orders for user 1
                $user1Orders = $this->orderService->getOrdersByUser($user1);
                $user1RetrievedIds = $user1Orders->pluck('id')->toArray();

                // Property: User 1's orders should not contain User 2's orders
                foreach ($user2OrderIds as $orderId) {
                    $this->assertNotContains(
                        $orderId,
                        $user1RetrievedIds,
                        "User 1's orders list should not contain User 2's order {$orderId}"
                    );
                }

                // Property: User 1 should only see their own orders
                $this->assertCount(
                    $user1OrderCount,
                    $user1Orders,
                    "User 1 should only see their own {$user1OrderCount} orders"
                );
            });
    }

    /**
     * Property 13: Orders List Contains Required Information
     *
     * For any order in the list, it should contain order number, date, total, and status.
     */
    public function testOrdersListContainsRequiredInformation(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5),  // number of orders
                Generator\elements(['pending', 'confirmed', 'processing', 'completed', 'cancelled'])
            )
            ->then(function (int $orderCount, string $status) {
                $user = User::factory()->create();

                // Create orders with specific status
                for ($i = 0; $i < $orderCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $user->id,
                        'total_amount' => rand(10000, 100000) / 100,
                        'delivery_address' => 'Test Address ' . $i,
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $status,
                    ]);
                }

                // Get orders
                $orders = $this->orderService->getOrdersByUser($user);

                // Property: Each order should have required fields
                foreach ($orders as $order) {
                    $this->assertNotNull($order->order_number, "Order should have order_number");
                    $this->assertNotNull($order->created_at, "Order should have created_at (date)");
                    $this->assertNotNull($order->total_amount, "Order should have total_amount");
                    $this->assertNotNull($order->order_status, "Order should have order_status");
                    $this->assertEquals($status, $order->order_status, "Order status should match");
                }
            });
    }
}
