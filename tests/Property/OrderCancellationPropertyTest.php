<?php

namespace Tests\Property;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use App\Services\OrderService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 22: Order Cancellation Updates Status**
 * **Validates: Requirements 12.2**
 *
 * For any order with status "pending", cancelling it should update the status to "cancelled" and record the timestamp.
 */
class OrderCancellationPropertyTest extends TestCase
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
     * Property 22: Order Cancellation Updates Status to Cancelled
     *
     * For any order with status "pending", cancelling it should update the status to "cancelled".
     */
    public function testPendingOrderCancellationUpdatesStatusToCancelled(): void
    {
        $this
            ->forAll(
                Generator\choose(100, 100000),  // total amount in cents
                Generator\elements(['cod', 'transfer_bank'])  // payment method
            )
            ->then(function (int $totalCents, string $paymentMethod) {
                $user = User::factory()->create();
                $totalAmount = $totalCents / 100;

                // Create a pending order
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'delivery_address' => 'Test Address',
                    'payment_method' => $paymentMethod,
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

                // Cancel the order
                $result = $this->orderService->cancelOrder($order);

                // Property: Cancellation should succeed for pending orders
                $this->assertTrue($result, "Cancellation should succeed for pending orders");

                // Property: Order status should be updated to cancelled
                $order->refresh();
                $this->assertEquals(
                    'cancelled',
                    $order->order_status,
                    "Order status should be updated to 'cancelled'"
                );
            });
    }

    /**
     * Property 22: Order Cancellation Records Timestamp
     *
     * For any cancelled order, the cancellation timestamp should be recorded in status history.
     */
    public function testOrderCancellationRecordsTimestamp(): void
    {
        $this
            ->forAll(
                Generator\choose(100, 100000)  // total amount in cents
            )
            ->then(function (int $totalCents) {
                $user = User::factory()->create();
                $totalAmount = $totalCents / 100;

                // Create a pending order
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
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

                $beforeCancellation = now()->subSecond();

                // Cancel the order
                $this->orderService->cancelOrder($order);

                // Property: A cancellation history entry should be created with timestamp
                $cancellationHistory = OrderStatusHistory::where('order_id', $order->id)
                    ->where('status', 'cancelled')
                    ->first();

                $this->assertNotNull(
                    $cancellationHistory,
                    "Cancellation history entry should exist"
                );

                $this->assertNotNull(
                    $cancellationHistory->created_at,
                    "Cancellation timestamp should be recorded"
                );

                // Property: Timestamp should be recent (within last 5 seconds)
                $this->assertTrue(
                    $cancellationHistory->created_at->greaterThanOrEqualTo($beforeCancellation),
                    "Cancellation timestamp should be recorded at or after the cancellation time"
                );
            });
    }

    /**
     * Property 22: Non-Pending Orders Cannot Be Cancelled
     *
     * For any order with status other than "pending", cancellation should fail.
     */
    public function testNonPendingOrdersCannotBeCancelled(): void
    {
        $this
            ->forAll(
                Generator\elements(['confirmed', 'processing', 'completed', 'cancelled'])
            )
            ->then(function (string $status) {
                $user = User::factory()->create();

                // Create an order with non-pending status
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => 100.00,
                    'delivery_address' => 'Test Address',
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => $status,
                ]);

                $originalStatus = $order->order_status;

                // Try to cancel the order
                $result = $this->orderService->cancelOrder($order);

                // Property: Cancellation should fail for non-pending orders
                $this->assertFalse(
                    $result,
                    "Cancellation should fail for orders with status '{$status}'"
                );

                // Property: Order status should remain unchanged
                $order->refresh();
                $this->assertEquals(
                    $originalStatus,
                    $order->order_status,
                    "Order status should remain '{$originalStatus}' after failed cancellation"
                );
            });
    }
}
