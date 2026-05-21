<?php

namespace Tests\Property;

use App\Models\Order;
use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 17: Order Filter by Status**
 * **Validates: Requirements 7.5**
 *
 * For any status filter applied, the filtered order list should only contain orders with the matching status.
 */
class OrderFilterPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
    }

    /**
     * Property 17: Order Filter by Status
     *
     * For any status filter applied, the filtered order list should only contain orders with the matching status.
     */
    public function testOrderFilterByStatusReturnsOnlyMatchingOrders(): void
    {
        $this
            ->forAll(
                Generator\elements(['pending', 'confirmed', 'processing', 'completed', 'cancelled']),
                Generator\choose(1, 5),
                Generator\choose(1, 5)
            )
            ->then(function (string $filterStatus, int $matchingCount, int $nonMatchingCount) {
                // Clear existing orders to ensure clean state for each iteration
                Order::query()->delete();
                User::query()->delete();
                
                // Create admin user
                $admin = User::factory()->create(['role' => 'admin']);
                
                // Create customer
                $customer = User::factory()->create(['role' => 'user']);

                // Get a different status for non-matching orders
                $allStatuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
                $otherStatuses = array_diff($allStatuses, [$filterStatus]);
                $otherStatus = $otherStatuses[array_rand($otherStatuses)];

                // Create orders with the filter status
                for ($i = 0; $i < $matchingCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $filterStatus,
                    ]);
                }

                // Create orders with different status
                for ($i = 0; $i < $nonMatchingCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $otherStatus,
                    ]);
                }

                // Access admin orders page with status filter
                $response = $this->actingAs($admin)->get(
                    route('admin.orders.index', ['status' => $filterStatus])
                );

                $response->assertStatus(200);

                // Get the orders from the view
                $viewOrders = $response->viewData('orders');

                // Property: All returned orders should have the filter status
                foreach ($viewOrders as $order) {
                    $this->assertEquals(
                        $filterStatus,
                        $order->order_status,
                        "All filtered orders should have status '{$filterStatus}'"
                    );
                }

                // Property: The count should match the number of orders with that status
                $this->assertEquals(
                    $matchingCount,
                    $viewOrders->total(),
                    "Filtered results should contain exactly {$matchingCount} orders"
                );
            });
    }

    /**
     * Property 17: Order Filter Returns All Orders When No Filter Applied
     *
     * When no status filter is applied, all orders should be returned.
     */
    public function testOrderFilterReturnsAllOrdersWhenNoFilter(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 10)
            )
            ->then(function (int $orderCount) {
                // Clear existing orders to ensure clean state for each iteration
                Order::query()->delete();
                User::query()->delete();
                
                // Create admin user
                $admin = User::factory()->create(['role' => 'admin']);
                
                // Create customer
                $customer = User::factory()->create(['role' => 'user']);

                $statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];

                // Create orders with random statuses
                for ($i = 0; $i < $orderCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $statuses[array_rand($statuses)],
                    ]);
                }

                // Access admin orders page without filter
                $response = $this->actingAs($admin)->get(route('admin.orders.index'));

                $response->assertStatus(200);

                // Get the orders from the view
                $viewOrders = $response->viewData('orders');

                // Property: All orders should be returned
                $this->assertEquals(
                    $orderCount,
                    $viewOrders->total(),
                    "All {$orderCount} orders should be returned when no filter is applied"
                );
            });
    }

    /**
     * Property 17: Order Filter by Payment Method
     *
     * For any payment method filter applied, the filtered order list should only contain orders with the matching payment method.
     */
    public function testOrderFilterByPaymentMethodReturnsOnlyMatchingOrders(): void
    {
        $this
            ->forAll(
                Generator\elements(['cod', 'transfer_bank']),
                Generator\choose(1, 5),
                Generator\choose(1, 5)
            )
            ->then(function (string $filterPaymentMethod, int $matchingCount, int $nonMatchingCount) {
                // Clear existing orders to ensure clean state for each iteration
                Order::query()->delete();
                User::query()->delete();
                
                // Create admin user
                $admin = User::factory()->create(['role' => 'admin']);
                
                // Create customer
                $customer = User::factory()->create(['role' => 'user']);

                // Get the other payment method
                $otherPaymentMethod = $filterPaymentMethod === 'cod' ? 'transfer_bank' : 'cod';

                // Create orders with the filter payment method
                for ($i = 0; $i < $matchingCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => $filterPaymentMethod,
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);
                }

                // Create orders with different payment method
                for ($i = 0; $i < $nonMatchingCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => $otherPaymentMethod,
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);
                }

                // Access admin orders page with payment method filter
                $response = $this->actingAs($admin)->get(
                    route('admin.orders.index', ['payment_method' => $filterPaymentMethod])
                );

                $response->assertStatus(200);

                // Get the orders from the view
                $viewOrders = $response->viewData('orders');

                // Property: All returned orders should have the filter payment method
                foreach ($viewOrders as $order) {
                    $this->assertEquals(
                        $filterPaymentMethod,
                        $order->payment_method,
                        "All filtered orders should have payment method '{$filterPaymentMethod}'"
                    );
                }

                // Property: The count should match the number of orders with that payment method
                $this->assertEquals(
                    $matchingCount,
                    $viewOrders->total(),
                    "Filtered results should contain exactly {$matchingCount} orders"
                );
            });
    }
}
