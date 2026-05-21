<?php

namespace Tests\Property;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Services\ReportService;
use Carbon\Carbon;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 24: Sales Report Date Range Filter**
 * **Validates: Requirements 20.2**
 *
 * For any date range filter applied, the sales report should only include orders within that date range.
 */
class SalesReportPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected ReportService $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportService = new ReportService();
        $this->limitTo(100);
    }

    /**
     * Property 24: Sales Report Date Range Filter - Orders Within Range Only
     *
     * For any date range filter applied, the sales report should only include orders within that date range.
     */
    public function testSalesReportOnlyIncludesOrdersWithinDateRange(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5),  // orders within range
                Generator\choose(1, 5),  // orders before range
                Generator\choose(1, 5)   // orders after range
            )
            ->then(function (int $withinCount, int $beforeCount, int $afterCount) {
                Order::query()->delete();
                User::query()->delete();

                $customer = User::factory()->create(['role' => 'user']);

                // Define date range: 15 days ago to 5 days ago
                $startDate = Carbon::today()->subDays(15);
                $endDate = Carbon::today()->subDays(5);

                $expectedRevenue = 0;

                // Create orders within the date range (completed) - use days 15-5 ago
                for ($i = 0; $i < $withinCount; $i++) {
                    $amount = rand(10000, 500000) / 100;
                    $expectedRevenue += $amount;
                    // Days 15-5 ago are within range [15 days ago, 5 days ago]
                    $daysAgo = 15 - ($i % 11); // Ensures we stay within 15-5 range (15, 14, 13, ..., 5)
                    $orderDate = Carbon::today()->subDays($daysAgo)->setHour(12);
                    
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => $amount,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }

                // Create orders before the date range (should not be included) - 20+ days ago
                for ($i = 0; $i < $beforeCount; $i++) {
                    $orderDate = Carbon::today()->subDays(20 + $i)->setHour(12);
                    
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }

                // Create orders after the date range (should not be included) - 1-4 days ago
                for ($i = 0; $i < $afterCount; $i++) {
                    $orderDate = Carbon::today()->subDays(1 + ($i % 4))->setHour(12);
                    
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 500000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }

                $summary = $this->reportService->getSalesByDateRange($startDate, $endDate);

                // Property: Only orders within the date range should be counted
                $this->assertEquals(
                    $withinCount,
                    $summary['total_orders'],
                    "Sales report should only include {$withinCount} orders within the date range"
                );

                // Property: Revenue should only include orders within the date range
                $this->assertEquals(
                    round($expectedRevenue, 2),
                    round((float)$summary['total_revenue'], 2),
                    "Sales report revenue should only include orders within the date range"
                );
            });
    }

    /**
     * Property 24: Sales Report Only Includes Completed Orders
     *
     * For any date range, the sales report should only include completed orders.
     */
    public function testSalesReportOnlyIncludesCompletedOrders(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5),  // completed orders
                Generator\choose(1, 5)   // non-completed orders
            )
            ->then(function (int $completedCount, int $otherCount) {
                Order::query()->delete();
                User::query()->delete();

                $customer = User::factory()->create(['role' => 'user']);

                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::now();

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

                // Create non-completed orders (should not be included)
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

                $summary = $this->reportService->getSalesByDateRange($startDate, $endDate);

                // Property: Only completed orders should be counted
                $this->assertEquals(
                    $completedCount,
                    $summary['total_orders'],
                    "Sales report should only include completed orders"
                );

                // Property: Revenue should only include completed orders
                $this->assertEquals(
                    round($expectedRevenue, 2),
                    round((float)$summary['total_revenue'], 2),
                    "Sales report revenue should only include completed orders"
                );
            });
    }


    /**
     * Property 24: Top Selling Products Within Date Range
     *
     * For any date range, the top selling products should only include sales from orders within that range.
     */
    public function testTopSellingProductsOnlyIncludesOrdersWithinDateRange(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 3),  // products
                Generator\choose(1, 3)   // orders per product within range
            )
            ->then(function (int $productCount, int $ordersPerProduct) {
                Order::query()->delete();
                OrderItem::query()->delete();
                Product::query()->delete();
                Category::query()->delete();
                User::query()->delete();

                $customer = User::factory()->create(['role' => 'user']);
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);

                // Define date range: 15 days ago to 5 days ago
                $startDate = Carbon::today()->subDays(15);
                $endDate = Carbon::today()->subDays(5);

                $products = [];
                $expectedQuantities = [];

                // Create products
                for ($i = 0; $i < $productCount; $i++) {
                    $product = Product::create([
                        'category_id' => $category->id,
                        'name' => 'Test Product ' . uniqid(),
                        'description' => 'Test Description',
                        'price' => rand(10000, 50000) / 100,
                        'is_available' => true,
                    ]);
                    $products[] = $product;
                    $expectedQuantities[$product->id] = 0;
                }

                // Create orders within date range - days 15-5 ago
                for ($i = 0; $i < $ordersPerProduct; $i++) {
                    $daysAgo = 15 - ($i % 11); // Ensures we stay within 15-5 range
                    $orderDate = Carbon::today()->subDays($daysAgo)->setHour(12);
                    
                    $order = Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => 0,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);

                    $totalAmount = 0;
                    foreach ($products as $product) {
                        $quantity = rand(1, 3);
                        $expectedQuantities[$product->id] += $quantity;
                        $subtotal = $product->price * $quantity;
                        $totalAmount += $subtotal;

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'product_price' => $product->price,
                            'quantity' => $quantity,
                            'subtotal' => $subtotal,
                        ]);
                    }

                    $order->update(['total_amount' => $totalAmount]);
                }

                // Create orders outside date range (should not be included) - 25 days ago
                $outsideOrder = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $customer->id,
                    'total_amount' => 0,
                    'delivery_address' => 'Test Address ' . uniqid(),
                    'payment_method' => 'cod',
                    'payment_status' => 'paid',
                    'order_status' => 'completed',
                    'created_at' => Carbon::today()->subDays(25)->setHour(12),
                    'updated_at' => Carbon::today()->subDays(25)->setHour(12),
                ]);

                foreach ($products as $product) {
                    OrderItem::create([
                        'order_id' => $outsideOrder->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $product->price,
                        'quantity' => 100, // Large quantity that should NOT be included
                        'subtotal' => $product->price * 100,
                    ]);
                }

                $topProducts = $this->reportService->getTopSellingProducts($startDate, $endDate);

                // Property: Top products should only include quantities from orders within date range
                foreach ($topProducts as $topProduct) {
                    $expectedQty = $expectedQuantities[$topProduct->product_id] ?? 0;
                    $this->assertEquals(
                        $expectedQty,
                        (int)$topProduct->total_quantity,
                        "Product {$topProduct->product_name} should have quantity {$expectedQty} within date range"
                    );
                }
            });
    }

    /**
     * Property 24: Daily Sales Trend Within Date Range
     *
     * For any date range, the daily sales trend should only include days within that range.
     */
    public function testDailySalesTrendOnlyIncludesDaysWithinDateRange(): void
    {
        $this
            ->forAll(
                Generator\choose(3, 7)  // range in days
            )
            ->then(function (int $rangeDays) {
                Order::query()->delete();
                User::query()->delete();

                $customer = User::factory()->create(['role' => 'user']);

                $startDate = Carbon::now()->subDays($rangeDays);
                $endDate = Carbon::now();

                // Create one completed order per day within range
                for ($i = 0; $i <= $rangeDays; $i++) {
                    $orderDate = Carbon::now()->subDays($i);
                    
                    Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $customer->id,
                        'total_amount' => rand(10000, 50000) / 100,
                        'delivery_address' => 'Test Address ' . uniqid(),
                        'payment_method' => 'cod',
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'created_at' => $orderDate,
                        'updated_at' => $orderDate,
                    ]);
                }

                // Create orders outside range
                Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $customer->id,
                    'total_amount' => rand(10000, 50000) / 100,
                    'delivery_address' => 'Test Address ' . uniqid(),
                    'payment_method' => 'cod',
                    'payment_status' => 'paid',
                    'order_status' => 'completed',
                    'created_at' => Carbon::now()->subDays($rangeDays + 5),
                    'updated_at' => Carbon::now()->subDays($rangeDays + 5),
                ]);

                $dailyTrend = $this->reportService->getDailySalesTrend($startDate, $endDate);

                // Property: Daily trend should have exactly rangeDays + 1 entries
                $this->assertCount(
                    $rangeDays + 1,
                    $dailyTrend,
                    "Daily trend should have exactly " . ($rangeDays + 1) . " days"
                );

                // Property: All dates in trend should be within the range
                foreach ($dailyTrend as $day) {
                    $dayDate = Carbon::parse($day['date']);
                    $this->assertTrue(
                        $dayDate->gte($startDate->startOfDay()) && $dayDate->lte($endDate->endOfDay()),
                        "Date {$day['date']} should be within the specified range"
                    );
                }
            });
    }
}
