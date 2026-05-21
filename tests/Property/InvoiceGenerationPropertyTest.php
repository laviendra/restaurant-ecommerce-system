<?php

namespace Tests\Property;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\InvoiceService;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 12: Unique Invoice Number Generation**
 * **Validates: Requirements 4.6**
 *
 * For any successfully created order, an invoice with a unique invoice number should be generated.
 */
class InvoiceGenerationPropertyTest extends TestCase
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
     * Property 12: Unique Invoice Number Generation
     *
     * For any successfully created order, an invoice with a unique invoice number should be generated.
     */
    public function testInvoiceNumberIsUniqueForEachOrder(): void
    {
        $this
            ->forAll(
                Generator\choose(2, 10)  // number of orders to create
            )
            ->then(function (int $orderCount) {
                $user = User::factory()->create();
                $category = Category::create([
                    'name' => 'Test Category',
                    'slug' => 'test-category-' . uniqid(),
                ]);

                $invoiceNumbers = [];

                for ($i = 0; $i < $orderCount; $i++) {
                    // Create an order
                    $order = Order::create([
                        'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                        'user_id' => $user->id,
                        'total_amount' => rand(10000, 100000) / 100,
                        'delivery_address' => 'Test Address ' . $i,
                        'payment_method' => 'cod',
                        'payment_status' => 'pending',
                        'order_status' => 'pending',
                    ]);

                    // Generate invoice
                    $invoice = $this->invoiceService->generateInvoice($order);

                    // Collect invoice numbers
                    $invoiceNumbers[] = $invoice->invoice_number;

                    // Property: Invoice should be created
                    $this->assertNotNull($invoice);
                    $this->assertNotNull($invoice->invoice_number);
                    $this->assertStringStartsWith('INV-', $invoice->invoice_number);
                }

                // Property: All invoice numbers should be unique
                $uniqueNumbers = array_unique($invoiceNumbers);
                $this->assertCount(
                    count($invoiceNumbers),
                    $uniqueNumbers,
                    "All invoice numbers should be unique. Found duplicates."
                );
            });
    }

    /**
     * Property 12: Invoice is associated with correct order
     *
     * For any generated invoice, it should be correctly associated with its order.
     */
    public function testInvoiceIsAssociatedWithCorrectOrder(): void
    {
        $this
            ->forAll(
                Generator\choose(100, 100000),  // total amount in cents
                Generator\elements(['cod', 'transfer_bank'])  // payment method
            )
            ->then(function (int $totalCents, string $paymentMethod) {
                $user = User::factory()->create();
                $totalAmount = $totalCents / 100;

                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => $totalAmount,
                    'delivery_address' => 'Test Address',
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                ]);

                $invoice = $this->invoiceService->generateInvoice($order);

                // Property: Invoice should be associated with the order
                $this->assertEquals($order->id, $invoice->order_id);

                // Property: Invoice data should contain order number
                $invoiceData = $invoice->invoice_data;
                $this->assertEquals($order->order_number, $invoiceData['order_number']);
            });
    }

    /**
     * Property 12: Generating invoice for same order returns existing invoice
     *
     * For any order that already has an invoice, generating again should return the existing invoice.
     */
    public function testGeneratingInvoiceForSameOrderReturnsExisting(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 5)  // number of times to call generateInvoice
            )
            ->then(function (int $callCount) {
                $user = User::factory()->create();

                $order = Order::create([
                    'order_number' => 'MCD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                    'user_id' => $user->id,
                    'total_amount' => 100.00,
                    'delivery_address' => 'Test Address',
                    'payment_method' => 'cod',
                    'payment_status' => 'pending',
                    'order_status' => 'pending',
                ]);

                // Generate invoice first time
                $firstInvoice = $this->invoiceService->generateInvoice($order);
                $firstInvoiceNumber = $firstInvoice->invoice_number;

                // Call generateInvoice multiple times
                for ($i = 0; $i < $callCount; $i++) {
                    $order->refresh();
                    $invoice = $this->invoiceService->generateInvoice($order);

                    // Property: Should return the same invoice
                    $this->assertEquals(
                        $firstInvoiceNumber,
                        $invoice->invoice_number,
                        "Generating invoice for same order should return existing invoice"
                    );
                }

                // Property: Only one invoice should exist for this order
                $invoiceCount = Invoice::where('order_id', $order->id)->count();
                $this->assertEquals(1, $invoiceCount, "Only one invoice should exist per order");
            });
    }
}
