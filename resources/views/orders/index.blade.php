@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
            <a href="{{ route('products.index') }}" class="text-red-600 hover:text-red-700 font-medium">
                Continue Shopping →
            </a>
        </div>

        <!-- Status Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('orders.index') }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition
                   {{ !$status ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All Orders
                </a>
                @foreach(['pending', 'confirmed', 'processing', 'completed', 'cancelled'] as $s)
                <a href="{{ route('orders.index', ['status' => $s]) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium transition
                   {{ $status === $s ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ ucfirst($s) }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Orders List -->
        @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $order->order_number }}</h3>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($order->order_status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $order->items->count() }} item(s) • 
                                {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-red-600">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500 mb-3">
                                Payment: 
                                <span class="font-medium 
                                    {{ $order->payment_status === 'paid' ? 'text-green-600' : ($order->payment_status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                            <a href="{{ route('orders.show', $order) }}" 
                               class="inline-block bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->appends(['status' => $status])->links() }}
        </div>
        @else
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
            <p class="text-gray-500 mb-6">
                @if($status)
                    You don't have any {{ $status }} orders.
                @else
                    You haven't placed any orders yet.
                @endif
            </p>
            <a href="{{ route('products.index') }}" class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition">
                Start Shopping
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
