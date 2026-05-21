<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #DA291C; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f9f9f9; }
        .order-details { background: white; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .item:last-child { border-bottom: none; }
        .total { font-size: 18px; font-weight: bold; margin-top: 15px; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>McDonald's</h1>
            <p>Order Confirmation</p>
        </div>
        
        <div class="content">
            <p>Dear {{ $order->user->name }},</p>
            <p>Thank you for your order! Your order has been received and is being processed.</p>
            
            <div class="order-details">
                <h3>Order Details</h3>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                <p><strong>Payment Method:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}</p>
                <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                @if($order->notes)
                <p><strong>Notes:</strong> {{ $order->notes }}</p>
                @endif
                
                <h4>Items:</h4>
                @foreach($order->items as $item)
                <div class="item">
                    <strong>{{ $item->product_name }}</strong> x {{ $item->quantity }}<br>
                    Rp {{ number_format($item->product_price, 0, ',', '.') }} each<br>
                    Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    @if($item->notes)
                    <br><em>Note: {{ $item->notes }}</em>
                    @endif
                </div>
                @endforeach
                
                <div class="total">
                    Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </div>
            </div>
            
            @if($order->invoice)
            <p><strong>Invoice Number:</strong> {{ $order->invoice->invoice_number }}</p>
            @endif
            
            <p>You can track your order status by logging into your account.</p>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing McDonald's!</p>
            <p>&copy; {{ date('Y') }} McDonald's. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
