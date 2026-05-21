<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Services\EmailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Display a listing of all orders with filters.
     * Requirements: 7.1, 7.5
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items']);

        // Filter by order status
        if ($request->filled('status')) {
            $query->where('order_status', $request->input('status'));
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->input('payment_method'));
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        // Search by order number or customer name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Get status counts for summary
        $statusCounts = Order::select('order_status', DB::raw('count(*) as count'))
            ->groupBy('order_status')
            ->pluck('count', 'order_status')
            ->toArray();

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified order detail.
     * Requirements: 7.2, 7.3, 15.3
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items', 'statusHistories', 'invoice']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status.
     * Requirements: 7.4, 18.2
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $validated['status'];

        // Update order status
        $updateData = ['order_status' => $newStatus];
        
        // Set timestamps for specific statuses
        if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
            $updateData['shipped_at'] = now();
        }
        
        if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
            $updateData['delivered_at'] = now();
        }
        
        // Add delivery notes if provided
        if ($validated['notes'] && in_array($newStatus, ['shipped', 'delivered'])) {
            $updateData['delivery_notes'] = $validated['notes'];
        }
        
        $order->update($updateData);

        // Record status history with timestamp
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'notes' => $validated['notes'] ?? "Status changed from {$oldStatus} to {$newStatus}",
            'created_at' => now(),
        ]);

        // Update payment status automatically for certain scenarios
        if ($newStatus === 'confirmed' && $order->payment_method === 'transfer_bank') {
            $order->update(['payment_status' => 'paid']);
        }
        
        // For COD orders, suggest updating payment status when delivered
        $message = "Order status updated to {$newStatus}.";
        if ($newStatus === 'delivered' && $order->payment_method === 'cod' && $order->payment_status === 'pending') {
            $message .= " Don't forget to update the payment status to 'Cash Collected' if payment was received.";
        }

        // Send email notification to customer
        try {
            $this->emailService->sendStatusUpdate($order, $newStatus);
        } catch (\Exception $e) {
            // Log error but don't fail the status update
            \Log::error('Failed to send status update email: ' . $e->getMessage());
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', $message);
    }

    /**
     * Update the payment status.
     * Requirements: 7.4
     */
    public function updatePaymentStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $oldPaymentStatus = $order->payment_status;
        $newPaymentStatus = $validated['payment_status'];
        
        $order->update(['payment_status' => $newPaymentStatus]);

        // Create appropriate success message based on payment method
        $message = 'Payment status updated successfully.';
        if ($order->payment_method === 'cod') {
            if ($newPaymentStatus === 'paid') {
                $message = 'Payment status updated to "Cash Collected". The cash payment has been confirmed.';
            } elseif ($newPaymentStatus === 'pending') {
                $message = 'Payment status updated to "Pending". Cash has not been collected yet.';
            } elseif ($newPaymentStatus === 'failed') {
                $message = 'Payment status updated to "Failed". There was an issue with cash collection.';
            }
        } else {
            if ($newPaymentStatus === 'paid') {
                $message = 'Payment status updated to "Paid". The bank transfer has been confirmed.';
            } elseif ($newPaymentStatus === 'pending') {
                $message = 'Payment status updated to "Pending". Waiting for payment confirmation.';
            } elseif ($newPaymentStatus === 'failed') {
                $message = 'Payment status updated to "Failed". The payment was not successful.';
            }
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', $message);
    }
}
