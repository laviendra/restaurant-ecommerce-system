<div class="p-8">
    <!-- Invoice Header -->
    <div class="flex justify-between items-start mb-8 pb-6 border-b-2 border-yellow-400">
        <div>
            <h1 class="text-3xl font-bold text-red-600">McDonald's</h1>
            <p class="text-gray-500 text-sm">E-Commerce Restaurant</p>
        </div>
        <div class="text-right">
            <h2 class="text-2xl font-semibold text-gray-800 mb-2">INVOICE</h2>
            <p class="text-gray-600">{{ $invoiceData['invoice_number'] }}</p>
        </div>
    </div>

    <!-- Invoice Meta Information -->
    <div class="grid grid-cols-3 gap-8 mb-8">
        <div>
            <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Invoice Date</h3>
            <p class="text-gray-800">{{ \Carbon\Carbon::parse($invoiceData['date'])->format('F d, Y') }}</p>
            <p class="text-gray-600 text-sm">{{ \Carbon\Carbon::parse($invoiceData['date'])->format('H:i') }}</p>
        </div>
        <div>
            <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Order Number</h3>
            <p class="text-gray-800 font-medium">{{ $invoiceData['order_number'] }}</p>
        </div>
        <div>
            <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Bill To</h3>
            <p class="text-gray-800 font-medium">{{ $invoiceData['customer']['name'] }}</p>
            <p class="text-gray-600 text-sm">{{ $invoiceData['customer']['email'] }}</p>
            <p class="text-gray-600 text-sm">{{ $invoiceData['customer']['phone'] }}</p>
        </div>
    </div>

    <!-- Delivery Address -->
    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
        <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Delivery Address</h3>
        <p class="text-gray-800">{{ $invoiceData['delivery_address'] }}</p>
    </div>

    <!-- Items Table -->
    <table class="w-full mb-8">
        <thead>
            <tr class="bg-yellow-400">
                <th class="text-left py-3 px-4 font-semibold text-gray-800 text-sm uppercase">Item</th>
                <th class="text-center py-3 px-4 font-semibold text-gray-800 text-sm uppercase">Qty</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-800 text-sm uppercase">Price</th>
                <th class="text-right py-3 px-4 font-semibold text-gray-800 text-sm uppercase">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoiceData['items'] as $item)
            <tr class="border-b border-gray-200">
                <td class="py-4 px-4">
                    <p class="text-gray-800 font-medium">{{ $item['name'] }}</p>
                    @if(!empty($item['notes']))
                    <p class="text-gray-500 text-xs italic mt-1">Note: {{ $item['notes'] }}</p>
                    @endif
                </td>
                <td class="py-4 px-4 text-center text-gray-600">{{ $item['quantity'] }}</td>
                <td class="py-4 px-4 text-right text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                <td class="py-4 px-4 text-right text-gray-800 font-medium">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="flex justify-end mb-8">
        <div class="w-72">
            <div class="flex justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600">Subtotal</span>
                <span class="text-gray-800">Rp {{ number_format($invoiceData['total_amount'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-3 border-b-2 border-red-600">
                <span class="text-lg font-bold text-gray-800">Total</span>
                <span class="text-lg font-bold text-red-600">Rp {{ number_format($invoiceData['total_amount'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="p-5 bg-gray-50 rounded-lg mb-6">
        <h3 class="font-semibold text-gray-800 mb-3">Payment Information</h3>
        <div class="flex items-center gap-4">
            <span class="text-gray-600">Method:</span>
            @if($invoiceData['payment_method'] === 'cod')
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                Cash on Delivery (COD)
            </span>
            @else
            <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                Bank Transfer
            </span>
            @endif
        </div>
    </div>

    <!-- Order Notes -->
    @if(!empty($invoiceData['notes']))
    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg mb-6">
        <h4 class="text-xs font-semibold text-gray-500 uppercase mb-1">Order Notes</h4>
        <p class="text-gray-700">{{ $invoiceData['notes'] }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-400 text-sm">
        <p>Thank you for your order!</p>
        <p class="mt-1">McDonald's E-Commerce - {{ config('app.name', 'McD E-Commerce') }}</p>
    </div>
</div>
