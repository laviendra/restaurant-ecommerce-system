<?php

namespace Tests\Property;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 16: Admin Order Status Update with Timestamp**
 * **Validates: Requirements 7.4**
 *
 * For any admin order status update, the new status should be saved with a recorded timestamp.
 */
class AdminOrderStatusUpdatePropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
    }

    /**
     * Property 16: Admin Order Status Update with Timestamp
     *
     * For any admin order status update, the new status should be saved with a recorded timestamp.
     */
    public function testAdminOrderStatusUpdateRecordsTimestamp(): void
    {
        $this
            ->forAll(
                Generator\elements(['pending', 'confirmed', 'processing', 'completed', 'cancelled']),
                Generator\elements(['confirmed', 'processing', 'completed', 'cancelled'])
            )
            ->then(function (string $initialStatus, string $newStatus) {
                // Create admin user
                $admin = User::factory()->create(['role' => 'admin']);
                
                // Create regular user for the order
                $customer = User::factory()->create(['role' => 'user']);

                // Create an order with initial status
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $customer->id,
                    'total_amount' => rand(10000, 500000) / 100,
                    'delivery_address' => 'Test Address ' . uniqid(),
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => $initialStatus,
                ]);

                // Record time before update
                $beforeUpdate = now();

                // Simulate admin updating order status via controller
                $response = $this->actingAs($admin)->patch(
                    route('admin.orders.update-status', $order),
                    [
                        'status' => $newStatus,
                        'notes' => 'Admin status update test',
                    ]
                );

                // Record time after update
                $afterUpdate = now();

                // Property: The order status should be updated
                $order->refresh();
                $this->assertEquals(
                    $newStatus,
                    $order->order_status,
                    "Order status should be updated to {$newStatus}"
                );

                // Property: A status history entry should be created with timestamp
                $latestHistory = OrderStatusHistory::where('order_id', $order->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $this->assertNotNull(
                    $latestHistory,
                    "A status history entry should be created"
                );

                $this->assertEquals(
                    $newStatus,
                    $latestHistory->status,
                    "Status history should have the new status"
                );

                // Property: The timestamp should be recorded
                $this->assertNotNull(
                    $latestHistory->created_at,
                    "Status history should have a timestamp"
                );

                // Property: The timestamp should be within the update window
                $this->assertTrue(
                    $latestHistory->created_at->greaterThanOrEqualTo($beforeUpdate->subSecond()),
                    "Timestamp should be after or equal to the time before update"
                );

                $this->assertTrue(
                    $latestHistory->created_at->lessThanOrEqualTo($afterUpdate->addSecond()),
                    "Timestamp should be before or equal to the time after update"
                );
            });
    }

    /**
     * Property 16: Admin Order Status Update Persists Notes
     *
     * For any admin order status update with notes, the notes should be saved with the status history.
     */
    public function testAdminOrderStatusUpdatePersistsNotes(): void
    {
        $this
            ->forAll(
                Generator\elements(['confirmed', 'processing', 'completed']),
                Generator\string()
            )
            ->then(function (string $newStatus, string $notes) {
                // Skip empty notes for this test
                $notes = trim($notes);
                if (empty($notes)) {
                    $notes = 'Default note';
                }
                
                // Limit notes length to avoid validation issues
                $notes = substr($notes, 0, 500);

                // Create admin user
                $admin = User::factory()->create(['role' => 'admin']);
                
                // Create regular user for the order
                $customer = User::factory()->create(['role' => 'user']);

                // Create an order
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $customer->id,
                    'total_amount' => rand(10000, 500000) / 100,
                    'delivery_address' => 'Test Address ' . uniqid(),
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                ]);

                // Simulate admin updating order status with notes
                $this->actingAs($admin)->patch(
                    route('admin.orders.update-status', $order),
                    [
                        'status' => $newStatus,
                        'notes' => $notes,
                    ]
                );

                // Property: The status history should contain the notes
                $latestHistory = OrderStatusHistory::where('order_id', $order->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $this->assertNotNull($latestHistory);
                $this->assertEquals(
                    $notes,
                    $latestHistory->notes,
                    "Status history should contain the provided notes"
                );
            });
    }
}
