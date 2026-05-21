<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $invoiceData['invoice_number'] }} - {{ config('app.name', 'McD E-Commerce') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            background: #fff;
        }
        
        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #FFC107;
        }
        
        .company-info h1 {
            font-size: 36px;
            font-weight: 700;
            color: #DA291C;
            margin-bottom: 8px;
        }
        
        .company-info p {
            color: #666;
            font-size: 14px;
            font-weight: 500;
        }
        
        .invoice-title {
            text-align: right;
        }
        
        .invoice-title h2 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
        }
        
        .invoice-title .invoice-number {
            font-size: 16px;
            color: #666;
            font-weight: 600;
        }
        
        /* Meta Information */
        .invoice-meta {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .meta-section h3 {
            font-size: 12px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        
        .meta-section p {
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
        }
        
        .meta-section p:first-of-type {
            font-weight: 600;
            color: #333;
            font-size: 16px;
        }
        
        /* Delivery Address */
        .delivery-section {
            margin-bottom: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .delivery-section h3 {
            font-size: 12px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 12px;
            letter-spacing: 1px;
        }
        
        .delivery-section p {
            color: #333;
            font-weight: 500;
            font-size: 15px;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        
        .items-table th {
            background: #FFC107;
            color: #333;
            font-weight: 700;
            padding: 16px 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .items-table th:last-child,
        .items-table td:last-child {
            text-align: right;
        }
        
        .items-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #eee;
            background: white;
        }
        
        .items-table tr:nth-child(even) td {
            background: #fafafa;
        }
        
        .item-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
            font-size: 15px;
        }
        
        .item-notes {
            font-size: 12px;
            color: #666;
            font-style: italic;
            background: #f5f5f5;
            padding: 6px 10px;
            border-radius: 4px;
            margin-top: 6px;
            display: inline-block;
        }
        
        /* Totals Section */
        .totals {
            margin-left: auto;
            width: 300px;
            margin-bottom: 40px;
        }
        
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            font-weight: 500;
        }
        
        .totals-row.total {
            font-size: 20px;
            font-weight: 700;
            color: #DA291C;
            border-bottom: 2px solid #DA291C;
            padding: 16px 0;
            margin-top: 8px;
        }
        
        /* Payment Information */
        .payment-info {
            margin-bottom: 40px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .payment-info h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #333;
        }
        
        .payment-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
        }
        
        .payment-badge.cod {
            background: #E8F5E9;
            color: #2E7D32;
        }
        
        .payment-badge.transfer {
            background: #E3F2FD;
            color: #1565C0;
        }
        
        /* Order Notes */
        .order-notes {
            margin-bottom: 40px;
            padding: 20px;
            background: #FFF8E1;
            border-left: 4px solid #FFC107;
            border-radius: 0 8px 8px 0;
        }
        
        .order-notes h4 {
            font-size: 14px;
            font-weight: 700;
            color: #F57C00;
            margin-bottom: 8px;
        }
        
        .order-notes p {
            color: #333;
            font-style: italic;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 30px 0;
            border-top: 1px solid #eee;
            color: #666;
        }
        
        .footer h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .footer p {
            font-size: 14px;
            margin-bottom: 4px;
        }
        
        .footer .company-name {
            color: #DA291C;
            font-weight: 600;
        }
        
        /* Print Styles */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .invoice-container {
                padding: 20px;
                box-shadow: none;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .invoice-meta {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .invoice-header {
                flex-direction: column;
                text-align: center;
            }
            
            .invoice-title {
                margin-top: 20px;
                text-align: center;
            }
            
            .totals {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>McDonald's</h1>
                <p>E-Commerce Restaurant</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p class="invoice-number">{{ $invoiceData['invoice_number'] }}</p>
            </div>
        </div>

        <!-- Invoice Meta Information -->
        <div class="invoice-meta">
            <div class="meta-section">
                <h3>Invoice Date</h3>
                <p>{{ \Carbon\Carbon::parse($invoiceData['date'])->format('F d, Y') }}</p>
                <p>{{ \Carbon\Carbon::parse($invoiceData['date'])->format('H:i') }}</p>
            </div>
            <div class="meta-section">
                <h3>Order Number</h3>
                <p>{{ $invoiceData['order_number'] }}</p>
            </div>
            <div class="meta-section">
                <h3>Bill To</h3>
                <p>{{ $invoiceData['customer']['name'] }}</p>
                <p>{{ $invoiceData['customer']['email'] }}</p>
                <p>{{ $invoiceData['customer']['phone'] ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="delivery-section">
            <h3>Delivery Address</h3>
            <p>{{ $invoiceData['delivery_address'] }}</p>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceData['items'] as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item['name'] }}</div>
                        @if(!empty($item['notes']))
                        <div class="item-notes">Note: {{ $item['notes'] }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item['quantity'] }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($invoiceData['total_amount'], 0, ',', '.') }}</span>
            </div>
            <div class="totals-row total">
                <span>Total</span>
                <span>Rp {{ number_format($invoiceData['total_amount'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="payment-info">
            <h3>Payment Information</h3>
            <div>
                <strong>Method: </strong>
                @if($invoiceData['payment_method'] === 'cod')
                <span class="payment-badge cod">Cash on Delivery</span>
                @else
                <span class="payment-badge transfer">Bank Transfer</span>
                @endif
            </div>
        </div>

        <!-- Order Notes -->
        @if(!empty($invoiceData['notes']))
        <div class="order-notes">
            <h4>Order Notes</h4>
            <p>{{ $invoiceData['notes'] }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <h3>Thank you for your order!</h3>
            <p><span class="company-name">McDonald's E-Commerce</span> - Laravel</p>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            // Small delay to ensure styles are loaded
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
