<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected EmailService $emailService;

    public function __construct(OrderService $orderService, EmailService $emailService)
    {
        $this->orderService = $orderService;
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of the user's orders.
     * Requirements: 5.1
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');
        
        $orders = $this->orderService->getOrdersByUser($user, $status);

        return view('orders.index', compact('orders', 'status'));
    }

    /**
     * Display the specified order.
     * Requirements: 5.2, 15.3
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load relationships
        $order->load(['items', 'statusHistories', 'invoice']);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel the specified order.
     * Requirements: 12.1, 12.2, 12.3
     */
    public function cancel(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Check if order can be cancelled (only pending orders)
        if ($order->order_status !== 'pending') {
            return back()->with('error', 'Only pending orders can be cancelled.');
        }

        $cancelled = $this->orderService->cancelOrder($order);

        if ($cancelled) {
            // Send status update email
            $this->emailService->sendStatusUpdate($order->fresh(), 'cancelled');
            
            return back()->with('success', 'Order has been cancelled successfully.');
        }

        return back()->with('error', 'Unable to cancel the order.');
    }

    /**
     * Confirm delivery of the order by customer.
     */
    public function confirmDelivery(Request $request, Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Check if order can be confirmed as delivered (only shipped orders)
        if ($order->order_status !== 'shipped') {
            return back()->with('error', 'Only shipped orders can be confirmed as delivered.');
        }

        $request->validate([
            'delivery_notes' => 'nullable|string|max:500',
        ]);

        // Update order status to delivered
        $order->update([
            'order_status' => 'delivered',
            'delivered_at' => now(),
            'delivery_notes' => $request->input('delivery_notes'),
        ]);

        // Record status history
        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => 'delivered',
            'notes' => 'Delivery confirmed by customer: ' . ($request->input('delivery_notes') ?: 'No additional notes'),
            'created_at' => now(),
        ]);

        // Send notification email
        try {
            $this->emailService->sendStatusUpdate($order, 'delivered');
        } catch (\Exception $e) {
            \Log::error('Failed to send delivery confirmation email: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you! Delivery has been confirmed successfully.');
    }
}
