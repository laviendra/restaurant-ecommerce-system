<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\InvoiceService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 18: Invoice Content Completeness**
 * **Validates: Requirements 8.1**
 *
 * For any generated invoice, it should contain order number, date, customer information,
 * item details, quantities, prices, subtotals, total amount, and payment method.
 */
class InvoiceContentPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected InvoiceService $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invoiceService = app(InvoiceService::class);
        $this->limitTo(100);
    }

    /**
     * Property 18: Invoice Content Completeness
     *
     * For any generated invoice, it should contain all required fields:
     * order number, date, customer information, item details, quantities,
     * prices, subtotals, total amount, and payment method.
     */
    public function testInvoiceContainsAllRequiredFields(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    fn($s) => strlen(trim($s)) > 0,
                    Generator\string()
                ),  // customer name
                Generator\elements(['cod', 'transfer_bank']),  // payment method
                Generator\choose(1, 5)  // number of items
            )
            ->then(function (string $customerName, string $paymentMethod, int $itemCount) {
                // Sanitize customer name
                $customerName = substr(preg_replace('/[^a-zA-Z0-9\s]/', '', $customerName) ?: 'Test User', 0, 50);
                if (empty(trim($customerName))) {
                    $customerName = 'Test User';
                }

                $user = User::factory()->create([
                    'name' => $customerName,
                    'email' => 'test' . uniqid() . '@example.com',
                    'phone' => '08123456789',
                ]);

                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);

                // Create order
                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => 0,
                    'delivery_address' => 'Test Delivery Address 123',
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                    'notes' => 'Test order notes',
                ]);

                // Create order items
                $totalAmount = 0;
                for ($i = 0; $i < $itemCount; $i++) {
                    $price = rand(1000, 10000) / 100;
                    $quantity = rand(1, 5);
                    $subtotal = $price * $quantity;
                    $totalAmount += $subtotal;

                    $product = Product::create([
                        'category_id' => $category->id,
                        'name' => 'Product ' . $i,
                        'price' => $price,
                        'is_available' => true,
                    ]);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }

                $order->update(['total_amount' => $totalAmount]);
                $order->refresh();

                // Generate invoice
                $invoice = $this->invoiceService->generateInvoice($order);
                $invoiceData = $invoice->invoice_data;

                // Property: Invoice should contain order number
                $this->assertArrayHasKey('order_number', $invoiceData);
                $this->assertEquals($order->order_number, $invoiceData['order_number']);

                // Property: Invoice should contain date
                $this->assertArrayHasKey('date', $invoiceData);
                $this->assertNotEmpty($invoiceData['date']);

                // Property: Invoice should contain customer information
                $this->assertArrayHasKey('customer', $invoiceData);
                $this->assertArrayHasKey('name', $invoiceData['customer']);
                $this->assertArrayHasKey('email', $invoiceData['customer']);
                $this->assertArrayHasKey('phone', $invoiceData['customer']);
                $this->assertEquals($user->name, $invoiceData['customer']['name']);
                $this->assertEquals($user->email, $invoiceData['customer']['email']);

                // Property: Invoice should contain item details
                $this->assertArrayHasKey('items', $invoiceData);
                $this->assertCount($itemCount, $invoiceData['items']);

                foreach ($invoiceData['items'] as $item) {
                    // Each item should have name, price, quantity, subtotal
                    $this->assertArrayHasKey('name', $item);
                    $this->assertArrayHasKey('price', $item);
                    $this->assertArrayHasKey('quantity', $item);
                    $this->assertArrayHasKey('subtotal', $item);
                    $this->assertNotEmpty($item['name']);
                    $this->assertGreaterThan(0, $item['price']);
                    $this->assertGreaterThan(0, $item['quantity']);
                    $this->assertGreaterThan(0, $item['subtotal']);
                }

                // Property: Invoice should contain total amount
                $this->assertArrayHasKey('total_amount', $invoiceData);
                $this->assertEquals(
                    round($totalAmount, 2),
                    round($invoiceData['total_amount'], 2)
                );

                // Property: Invoice should contain payment method
                $this->assertArrayHasKey('payment_method', $invoiceData);
                $this->assertEquals($paymentMethod, $invoiceData['payment_method']);
            });
    }

    /**
     * Property 18: Invoice item subtotals are correct
     *
     * For any invoice item, the subtotal should equal price × quantity.
     */
    public function testInvoiceItemSubtotalsAreCorrect(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 10),  // quantity
                Generator\choose(100, 10000)  // price in cents
            )
            ->then(function (int $quantity, int $priceInCents) {
                $price = $priceInCents / 100;
                $expectedSubtotal = $price * $quantity;

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

                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => $expectedSubtotal,
                    'delivery_address' => 'Test Address',
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                ]);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $expectedSubtotal,
                ]);

                $invoice = $this->invoiceService->generateInvoice($order);
                $invoiceData = $invoice->invoice_data;

                // Property: Item subtotal in invoice should equal price × quantity
                $this->assertCount(1, $invoiceData['items']);
                $item = $invoiceData['items'][0];

                $this->assertEquals(
                    round($expectedSubtotal, 2),
                    round($item['subtotal'], 2),
                    "Invoice item subtotal should equal price ({$price}) × quantity ({$quantity})"
                );
            });
    }

    /**
     * Property 18: Invoice total equals sum of item subtotals
     *
     * For any invoice, the total amount should equal the sum of all item subtotals.
     */
    public function testInvoiceTotalEqualsSumOfSubtotals(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5)  // number of items
            )
            ->then(function (int $itemCount) {
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);

                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => 0,
                    'delivery_address' => 'Test Address',
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                ]);

                $expectedTotal = 0;
                for ($i = 0; $i < $itemCount; $i++) {
                    $price = rand(100, 10000) / 100;
                    $quantity = rand(1, 10);
                    $subtotal = $price * $quantity;
                    $expectedTotal += $subtotal;

                    $product = Product::create([
                        'category_id' => $category->id,
                        'name' => 'Product ' . $i,
                        'price' => $price,
                        'is_available' => true,
                    ]);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $price,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                    ]);
                }

                $order->update(['total_amount' => $expectedTotal]);
                $order->refresh();

                $invoice = $this->invoiceService->generateInvoice($order);
                $invoiceData = $invoice->invoice_data;

                // Calculate sum of subtotals from invoice
                $sumOfSubtotals = array_sum(array_column($invoiceData['items'], 'subtotal'));

                // Property: Invoice total should equal sum of item subtotals
                $this->assertEquals(
                    round($expectedTotal, 2),
                    round($invoiceData['total_amount'], 2),
                    "Invoice total should equal sum of item subtotals"
                );

                $this->assertEquals(
                    round($sumOfSubtotals, 2),
                    round($invoiceData['total_amount'], 2),
                    "Invoice total should equal calculated sum of subtotals"
                );
            });
    }
}
