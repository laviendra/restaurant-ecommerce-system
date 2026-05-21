<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Services\OrderService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 14: Order Status Change Persistence**
 * **Validates: Requirements 5.3**
 *
 * For any order status update, the new status should be persisted and reflected in the order detail.
 */
class OrderStatusChangePropertyTest extends TestCase
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
     * Property 14: Order Status Change Persistence
     *
     * For any order status update, the new status should be persisted and reflected in the order detail.
     */
    public function testOrderStatusChangeIsPersisted(): void
    {
        $this
            ->forAll(
                Generator\elements(['pending', 'confirmed', 'processing', 'completed', 'cancelled']),
                Generator\elements(['pending', 'confirmed', 'processing', 'completed', 'cancelled'])
            )
            ->then(function (string $initialStatus, string $newStatus) {
                $user = User::factory()->create();

                // Create an order with initial status
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => rand(10000, 100000) / 100,
                    'delivery_address' => 'Test Address',
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => $initialStatus,
                ]);

                // Create initial status history
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => $initialStatus,
                    'notes' => 'Initial status',
                    'created_at' => now(),
                ]);

                // Update the status
                $updatedOrder = $this->orderService->updateStatus($order, $newStatus, 'Status updated');

                // Property: The order status should be updated
                $this->assertEquals(
                    $newStatus,
                    $updatedOrder->order_status,
                    "Order status should be updated to {$newStatus}"
                );

                // Property: The status should be persisted in the database
                $order->refresh();
                $this->assertEquals(
                    $newStatus,
                    $order->order_status,
                    "Order status should be persisted in database"
                );
            });
    }

    /**
     * Property 14: Order Status Change Creates History Entry
     *
     * For any order status update, a new status history entry should be created.
     */
    public function testOrderStatusChangeCreatesHistoryEntry(): void
    {
        $this
            ->forAll(
                Generator\elements(['confirmed', 'processing', 'completed', 'cancelled'])
            )
            ->then(function (string $newStatus) {
                $user = User::factory()->create();

                // Create an order
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => rand(10000, 100000) / 100,
                    'delivery_address' => 'Test Address',
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                ]);

                // Create initial status history
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'notes' => 'Order created',
                    'created_at' => now(),
                ]);

                $initialHistoryCount = OrderStatusHistory::where('order_id', $order->id)->count();

                // Update the status
                $this->orderService->updateStatus($order, $newStatus, 'Status changed');

                // Property: A new history entry should be created
                $newHistoryCount = OrderStatusHistory::where('order_id', $order->id)->count();
                $this->assertEquals(
                    $initialHistoryCount + 1,
                    $newHistoryCount,
                    "A new status history entry should be created"
                );

                // Property: The latest history entry should have the new status
                $latestHistory = OrderStatusHistory::where('order_id', $order->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $this->assertEquals(
                    $newStatus,
                    $latestHistory->status,
                    "Latest history entry should have the new status"
                );
            });
    }

    /**
     * Property 14: Order Status Change Reflects in Order Detail
     *
     * For any order status update, the order detail should reflect the new status.
     */
    public function testOrderStatusChangeReflectsInOrderDetail(): void
    {
        $this
            ->forAll(
                Generator\elements(['confirmed', 'processing', 'completed'])
            )
            ->then(function (string $newStatus) {
                $user = User::factory()->create();

                // Create an order
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => rand(10000, 100000) / 100,
                    'delivery_address' => 'Test Address',
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                ]);

                // Update the status
                $this->orderService->updateStatus($order, $newStatus);

                // Get order by ID (simulating viewing order detail)
                $orderDetail = $this->orderService->getOrderById($order->id, $user);

                // Property: Order detail should show the new status
                $this->assertNotNull($orderDetail);
                $this->assertEquals(
                    $newStatus,
                    $orderDetail->order_status,
                    "Order detail should reflect the new status"
                );

                // Property: Status histories should be loaded
                $this->assertTrue(
                    $orderDetail->relationLoaded('statusHistories'),
                    "Status histories should be loaded with order detail"
                );
            });
    }
}
