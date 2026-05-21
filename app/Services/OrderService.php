<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cartService;
    protected InvoiceService $invoiceService;

    public function __construct(CartService $cartService, InvoiceService $invoiceService)
    {
        $this->cartService = $cartService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Generate a unique order number.
     */
    public function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'MCD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Create an order from the user's cart.
     */
    public function createOrder(
        User $user,
        string $deliveryAddress,
        ?string $notes,
        string $paymentMethod,
        ?string $paymentProof = null
    ): Order {
        return DB::transaction(function () use ($user, $deliveryAddress, $notes, $paymentMethod, $paymentProof) {
            $cartItems = $this->cartService->getCartItems($user);
            $total = $this->cartService->getTotal($user);

            // Create the order
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user->id,
                'total_amount' => $total,
                'delivery_address' => $deliveryAddress,
                'notes' => $notes,
                'payment_method' => $paymentMethod,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_proof' => $paymentProof,
            ]);

            // Create order items from cart items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->getSubtotal(),
                    'notes' => $cartItem->notes,
                ]);
            }

            // Create initial status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Order created',
                'created_at' => now(),
            ]);

            // Generate invoice using InvoiceService
            $this->invoiceService->generateInvoice($order);

            // Clear the cart
            $this->cartService->clearCart($user);

            return $order->fresh(['items', 'invoice', 'statusHistories']);
        });
    }

    /**
     * Update order status.
     */
    public function updateStatus(Order $order, string $status, ?string $notes = null): Order
    {
        return DB::transaction(function () use ($order, $status, $notes) {
            $order->update(['order_status' => $status]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => $status,
                'notes' => $notes,
                'created_at' => now(),
            ]);

            return $order->fresh(['statusHistories']);
        });
    }

    /**
     * Update payment proof for an order.
     */
    public function updatePaymentProof(Order $order, string $paymentProofPath): Order
    {
        $order->update(['payment_proof' => $paymentProofPath]);
        return $order->fresh();
    }

    /**
     * Get orders for a user.
     */
    public function getOrdersByUser(User $user, ?string $status = null)
    {
        $query = Order::where('user_id', $user->id)
            ->with(['items', 'statusHistories'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('order_status', $status);
        }

        return $query->paginate(10);
    }

    /**
     * Get order by ID for a user.
     */
    public function getOrderById(int $orderId, User $user): ?Order
    {
        return Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->with(['items', 'statusHistories', 'invoice'])
            ->first();
    }

    /**
     * Cancel an order (only if pending).
     */
    public function cancelOrder(Order $order): bool
    {
        if ($order->order_status !== 'pending') {
            return false;
        }

        $this->updateStatus($order, 'cancelled', 'Order cancelled by customer');
        return true;
    }
}
