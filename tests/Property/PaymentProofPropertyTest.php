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
 * **Feature: restaurant-ecommerce, Property 11: Payment Proof Association**
 * **Validates: Requirements 4.5**
 *
 * For any uploaded payment proof image, the image should be saved and
 * correctly associated with the corresponding order.
 */
class PaymentProofPropertyTest extends TestCase
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
     * Property 11: Payment Proof Association
     *
     * For any uploaded payment proof image path, the path should be saved and
     * correctly associated with the corresponding order.
     */
    public function testPaymentProofIsAssociatedWithOrder(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    function ($s) { return strlen(trim($s)) > 0 && strlen($s) <= 255; },
                    Generator\string()
                )
            )
            ->then(function (string $paymentProofPath) {
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

                // Create order with payment proof (transfer_bank)
                $order = $this->orderService->createOrder(
                    $user,
                    'Test Address 123',
                    null,
                    'transfer_bank',
                    $paymentProofPath
                );

                // Property: Payment proof path should be saved to order
                $this->assertEquals(
                    $paymentProofPath,
                    $order->payment_proof,
                    "Order payment_proof should match the provided path"
                );

                // Property: Payment method should be 'transfer_bank'
                $this->assertEquals(
                    'transfer_bank',
                    $order->payment_method,
                    "Order payment_method should be 'transfer_bank'"
                );
            });
    }

    /**
     * Property 11: Payment Proof Update
     *
     * For any order, updating the payment proof should persist the new path.
     */
    public function testPaymentProofCanBeUpdated(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    function ($s) { return strlen(trim($s)) > 0 && strlen($s) <= 255; },
                    Generator\string()
                ),
                Generator\suchThat(
                    function ($s) { return strlen(trim($s)) > 0 && strlen($s) <= 255; },
                    Generator\string()
                )
            )
            ->then(function (string $initialPath, string $newPath) {
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

                // Create order with initial payment proof
                $order = $this->orderService->createOrder(
                    $user,
                    'Test Address 123',
                    null,
                    'transfer_bank',
                    $initialPath
                );

                // Update payment proof
                $updatedOrder = $this->orderService->updatePaymentProof($order, $newPath);

                // Property: Updated payment proof should be persisted
                $this->assertEquals(
                    $newPath,
                    $updatedOrder->payment_proof,
                    "Updated payment_proof should match the new path"
                );
            });
    }
}
