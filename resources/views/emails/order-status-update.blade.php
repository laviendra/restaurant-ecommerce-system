<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #DA291C; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-weight: bold; }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-confirmed { background: #DBEAFE; color: #1E40AF; }
        .status-processing { background: #E0E7FF; color: #3730A3; }
        .status-completed { background: #D1FAE5; color: #065F46; }
        .status-cancelled { background: #FEE2E2; color: #991B1B; }
        .order-details { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>McDonald's</h1>
            <p>Order Status Update</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $order->user->name }},</p>
            <p>Your order status has been updated.</p>
            
            <div class="order-details">
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>New Status:</strong> 
                    <span class="status-badge status-{{ $newStatus }}">
                        {{ ucfirst($newStatus) }}
                    </span>
                </p>
            </div>
            
            @if($newStatus === 'confirmed')
            <p>Your order has been confirmed and will be prepared shortly.</p>
            @elseif($newStatus === 'processing')
            <p>Your order is now being prepared.</p>
            @elseif($newStatus === 'completed')
            <p>Your order has been completed. Thank you for ordering with us!</p>
            @elseif($newStatus === 'cancelled')
            <p>Your order has been cancelled. If you have any questions, please contact us.</p>
            @endif
            
            <p>You can view your order details by logging into your account.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing McDonald's!</p>
            <p>&copy; {{ date('Y') }} McDonald's. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
