<?php

namespace App\Services;

use App\Models\Order;
use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdate;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Send order confirmation email with invoice.
     */
    public function sendOrderConfirmation(Order $order): void
    {
        $order->load(['items', 'user', 'invoice']);
        
        Mail::to($order->user->email)
            ->send(new OrderConfirmation($order));
    }

    /**
     * Send order status update email.
     */
    public function sendStatusUpdate(Order $order, string $newStatus): void
    {
        $order->load(['items', 'user']);
        
        Mail::to($order->user->email)
            ->send(new OrderStatusUpdate($order, $newStatus));
    }
}
