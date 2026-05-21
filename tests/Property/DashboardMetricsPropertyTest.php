<?php

namespace Tests\Property;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 20: Dashboard Metrics Accuracy**
 * **Validates: Requirements 10.1, 10.2, 10.3**
 *
 * For any set of orders in the database, the dashboard should display accurate total orders count,
 * total revenue, pending orders count, orders grouped by status, and today's orders/revenue filtered correctly.
 */
class DashboardMetricsPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
    }

    /**
     * Property 20: Total Orders Count Accuracy
     *
     * For any set of orders, the dashboard should display the accurate total orders count.
     */
    public function testTotalOrdersCountIsAccurate(): void
    {
        $this
            ->forAll(
                Generator\choose(0, 20)
            )
            ->then(function (int $orderCount) {
                Order::query()->delete();
                User::query()->delete();

                $admin = User::factory()->create(['role' => 'admin']);
                $customer = User::factory()->create(['role' => 'user']);

                $statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];

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

                $controller = new DashboardController();
                $metrics = $controller->getMetrics();

                $this->assertEquals(
                    $orderCount,
                    $metrics['total_orders'],
                    "Total orders count should be {$orderCount}"
                );
            });
    }

    /**
     * Property 20: Total Revenue Accuracy (Completed Orders Only)
     *
     * For any set of orders, the dashboard should display accurate total revenue from completed orders only.
     */
    public function testTotalRevenueIsAccurateForCompletedOrders(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 10),
                Generator\choose(1, 10)
            )
            ->then(function (int $completedCount, int $otherCount) {
                Order::query()->delete();
                User::query()->delete();

                $admin = User::factory()->create(['role' => 'admin']);
                $customer = User::factory()->create(['role' => 'user']);

                $expectedRevenue = 0;
                $otherStatuses = ['pending', 'confirmed', 'processing', 'cancelled'];

                // Create completed orders
                for ($i = 0; $i < $completedCount; $i++) {
                    $amount = rand(10000, 500000) / 100;
                    $expectedRevenue += $amount;
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => $amount,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                    ]);
                }

                // Create non-completed orders (should not count towards revenue)
                for ($i = 0; $i < $otherCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $otherStatuses[array_rand($otherStatuses)],
                    ]);
                }

                $controller = new DashboardController();
                $metrics = $controller->getMetrics();

                $this->assertEquals(
                    round($expectedRevenue, 2),
                    round((float)$metrics['total_revenue'], 2),
                    "Total revenue should only include completed orders"
                );
            });
    }

    /**
     * Property 20: Pending Orders Count Accuracy
     *
     * For any set of orders, the dashboard should display accurate pending orders count.
     */
    public function testPendingOrdersCountIsAccurate(): void
    {
        $this
            ->forAll(
                Generator\choose(0, 10),
                Generator\choose(0, 10)
            )
            ->then(function (int $pendingCount, int $otherCount) {
                Order::query()->delete();
                User::query()->delete();

                $admin = User::factory()->create(['role' => 'admin']);
                $customer = User::factory()->create(['role' => 'user']);

                $otherStatuses = ['confirmed', 'processing', 'completed', 'cancelled'];

                // Create pending orders
                for ($i = 0; $i < $pendingCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);
                }

                // Create non-pending orders
                for ($i = 0; $i < $otherCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $otherStatuses[array_rand($otherStatuses)],
                    ]);
                }

                $controller = new DashboardController();
                $metrics = $controller->getMetrics();

                $this->assertEquals(
                    $pendingCount,
                    $metrics['pending_orders'],
                    "Pending orders count should be {$pendingCount}"
                );
            });
    }


    /**
     * Property 20: Orders Grouped by Status Accuracy
     *
     * For any set of orders, the dashboard should display accurate counts for each status.
     */
    public function testOrdersGroupedByStatusIsAccurate(): void
    {
        $this
            ->forAll(
                Generator\choose(0, 5),
                Generator\choose(0, 5),
                Generator\choose(0, 5),
                Generator\choose(0, 5),
                Generator\choose(0, 5)
            )
            ->then(function (int $pending, int $confirmed, int $processing, int $completed, int $cancelled) {
                Order::query()->delete();
                User::query()->delete();

                $admin = User::factory()->create(['role' => 'admin']);
                $customer = User::factory()->create(['role' => 'user']);

                $statusCounts = [
                    'pending' => $pending,
                    'confirmed' => $confirmed,
                    'processing' => $processing,
                    'completed' => $completed,
                    'cancelled' => $cancelled,
                ];

                foreach ($statusCounts as $status => $count) {
                    for ($i = 0; $i < $count; $i++) {
                        Order::create([
                            'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                            'user_id' => $customer->id,
                            'total_amount' => rand(10000, 500000) / 100,
                            'delivery_address' => 'Test Address ' . uniqid(),
                            'payment_method' => 'cod',
                            'payment_status' => 'pending',
                            'order_status' => $status,
                        ]);
                    }
                }

                $controller = new DashboardController();
                $ordersByStatus = $controller->getOrdersByStatus();

                foreach ($statusCounts as $status => $expectedCount) {
                    $actualCount = $ordersByStatus[$status] ?? 0;
                    $this->assertEquals(
                        $expectedCount,
                        $actualCount,
                        "Orders with status '{$status}' should be {$expectedCount}"
                    );
                }
            });
    }

    /**
     * Property 20: Today's Orders Count Accuracy
     *
     * For any set of orders, the dashboard should display accurate today's orders count.
     */
    public function testTodayOrdersCountIsAccurate(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 10),
                Generator\choose(1, 10)
            )
            ->then(function (int $todayCount, int $pastCount) {
                // Create a unique user for this iteration
                $customer = User::factory()->create(['role' => 'user']);

                $statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];

                // Create today's orders
                for ($i = 0; $i < $todayCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $statuses[array_rand($statuses)],
                        'created_at' => Carbon::now(),
                    ]);
                }

                // Create past orders (yesterday)
                for ($i = 0; $i < $pastCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $statuses[array_rand($statuses)],
                        'created_at' => Carbon::yesterday(),
                    ]);
                }

                $controller = new DashboardController();
                $todayStats = $controller->getTodayStats();

                // Get the actual count from database
                $actualDbCount = Order::whereDate('created_at', Carbon::today())->count();
                
                // Property: The dashboard should show the same count as the database query
                $this->assertEquals(
                    $actualDbCount,
                    $todayStats['orders'],
                    "Today's orders count from dashboard should match database query"
                );
            });
    }

    /**
     * Property 20: Today's Revenue Accuracy (Completed Orders Only)
     *
     * For any set of orders, the dashboard should display accurate today's revenue from completed orders only.
     */
    public function testTodayRevenueIsAccurateForCompletedOrders(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5),
                Generator\choose(1, 5),
                Generator\choose(1, 5)
            )
            ->then(function (int $todayCompletedCount, int $todayOtherCount, int $pastCompletedCount) {
                // Create a unique user for this iteration
                $customer = User::factory()->create(['role' => 'user']);

                $otherStatuses = ['pending', 'confirmed', 'processing', 'cancelled'];

                // Create today's completed orders
                for ($i = 0; $i < $todayCompletedCount; $i++) {
                    $amount = rand(10000, 500000) / 100;
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => $amount,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'created_at' => Carbon::now(),
                    ]);
                }

                // Create today's non-completed orders (should not count)
                for ($i = 0; $i < $todayOtherCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => $otherStatuses[array_rand($otherStatuses)],
                        'created_at' => Carbon::now(),
                    ]);
                }

                // Create past completed orders (should not count)
                for ($i = 0; $i < $pastCompletedCount; $i++) {
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'created_at' => Carbon::yesterday(),
                    ]);
                }

                $controller = new DashboardController();
                $todayStats = $controller->getTodayStats();

                // Get the actual revenue from database
                $actualDbRevenue = (float) Order::whereDate('created_at', Carbon::today())
                    ->where('order_status', 'completed')
                    ->sum('total_amount');

                // Property: The dashboard should show the same revenue as the database query
                $this->assertEquals(
                    round($actualDbRevenue, 2),
                    round((float)$todayStats['revenue'], 2),
                    "Today's revenue from dashboard should match database query"
                );
            });
    }

    /**
     * Property 20: Recent Orders List Accuracy
     *
     * For any set of orders, the dashboard should display the most recent orders (up to 10).
     */
    public function testRecentOrdersListIsAccurate(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 20)
            )
            ->then(function (int $orderCount) {
                Order::query()->delete();
                User::query()->delete();

                $admin = User::factory()->create(['role' => 'admin']);
                $customer = User::factory()->create(['role' => 'user']);

                $statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];

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

                $controller = new DashboardController();
                $recentOrders = $controller->getRecentOrders();

                $expectedCount = min($orderCount, 10);
                $this->assertCount(
                    $expectedCount,
                    $recentOrders,
                    "Recent orders should contain at most 10 orders"
                );

                // Verify orders are sorted by created_at desc
                $previousDate = null;
                foreach ($recentOrders as $order) {
                    if ($previousDate !== null) {
                        $this->assertGreaterThanOrEqual(
                            $order->created_at,
                            $previousDate,
                            "Recent orders should be sorted by created_at descending"
                        );
                    }
                    $previousDate = $order->created_at;
                }
            });
    }
}
