@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-green-800 mb-2">Order Placed Successfully!</h1>
            <p class="text-green-600">Thank you for your order. We'll start preparing it soon.</p>
        </div>

        <!-- Order Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Order Details</h2>
                    <p class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium 
                    @if($order->order_status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->order_status === 'confirmed') bg-blue-100 text-blue-800
                    @elseif($order->order_status === 'processing') bg-indigo-100 text-indigo-800
                    @elseif($order->order_status === 'completed') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($order->order_status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500">Order Number</p>
                    <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="font-semibold text-gray-900">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}
                    </p>
                </div>
                @if($order->invoice)
                <div>
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <p class="font-semibold text-gray-900">{{ $order->invoice->invoice_number }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <p class="font-semibold 
                        @if($order->payment_status === 'pending') text-yellow-600
                        @elseif($order->payment_status === 'paid') text-green-600
                        @else text-red-600
                        @endif">
                        {{ ucfirst($order->payment_status) }}
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Delivery Address</p>
                <p class="text-gray-900">{{ $order->delivery_address }}</p>
            </div>

            @if($order->notes)
            <div class="mb-6">
                <p class="text-sm text-gray-500 mb-1">Order Notes</p>
                <p class="text-gray-900">{{ $order->notes }}</p>
            </div>
            @endif

            <!-- Order Items -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="font-semibold text-gray-900 mb-4">Items Ordered</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $item->quantity }} × Rp {{ number_format($item->product_price, 0, ',', '.') }}</p>
                            @if($item->notes)
                            <p class="text-sm text-gray-500 italic">Note: {{ $item->notes }}</p>
                            @endif
                        </div>
                        <span class="font-medium text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total</span>
                        <span class="text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('products.index') }}" class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg font-semibold text-center hover:bg-red-700 transition">
                Continue Shopping
            </a>
            <a href="{{ route('orders.show', $order) }}" class="flex-1 bg-white text-gray-700 py-3 px-6 rounded-lg font-semibold text-center border border-gray-300 hover:bg-gray-50 transition">
                View Order Details
            </a>
        </div>
    </div>
</div>
@endsection
