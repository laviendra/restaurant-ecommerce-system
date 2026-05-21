@extends('layouts.admin')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Orders
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Order {{ $order->order_number }}</h1>
            <p class="mt-1 text-sm text-gray-600">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.invoices.show', $order) }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                View Invoice
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Items</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <div class="px-6 py-4">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">{{ $item->product_name }}</h3>
                                <p class="text-sm text-gray-500">
                                    Rp {{ number_format($item->product_price, 0, ',', '.') }} × {{ $item->quantity }}
                                </p>
                                @if($item->notes)
                                <div class="mt-2 p-2 bg-yellow-50 rounded-md">
                                    <p class="text-xs text-yellow-800">
                                        <span class="font-medium">Note:</span> {{ $item->notes }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between text-base font-medium text-gray-900">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Tracking -->
            @if($order->shipped_at || $order->delivered_at)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Delivery Tracking</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if($order->shipped_at)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Shipped At</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->shipped_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    
                    @if($order->delivered_at)
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Delivered At</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->delivered_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    
                    @if($order->delivery_notes)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Delivery Notes</p>
                        <p class="text-sm text-gray-700">{{ $order->delivery_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Order Notes -->
            @if($order->notes)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Order Notes</h2>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                </div>
            </div>
            @endif

            <!-- Payment Proof (for Transfer Bank) -->
            @if($order->payment_method === 'transfer_bank' && $order->payment_proof)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Payment Proof</h2>
                </div>
                <div class="px-6 py-4">
                    <a href="{{ Storage::url($order->payment_proof) }}" target="_blank" class="block">
                        <img src="{{ Storage::url($order->payment_proof) }}" 
                             alt="Payment Proof" 
                             class="max-w-full h-auto max-h-96 rounded-lg border border-gray-200 hover:opacity-90 transition">
                    </a>
                    <p class="mt-2 text-xs text-gray-500">Click image to view full size</p>
                </div>
            </div>
            @endif

            <!-- Status History -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Status History</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($order->statusHistories as $history)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @php
                                                $statusIcons = [
                                                    'pending' => 'bg-yellow-500',
                                                    'confirmed' => 'bg-blue-500',
                                                    'processing' => 'bg-purple-500',
                                                    'completed' => 'bg-green-500',
                                                    'cancelled' => 'bg-red-500',
                                                ];
                                            @endphp
                                            <span class="h-8 w-8 rounded-full {{ $statusIcons[$history->status] ?? 'bg-gray-500' }} flex items-center justify-center ring-8 ring-white">
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($history->status) }}</p>
                                                @if($history->notes)
                                                <p class="text-sm text-gray-500">{{ $history->notes }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $history->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Customer</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</p>
                        @if($order->user->phone ?? null)
                        <p class="text-sm text-gray-500">{{ $order->user->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Delivery Address</h2>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm text-gray-700">{{ $order->delivery_address }}</p>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Payment</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Method</span>
                        <span class="text-sm font-medium text-gray-900">
                            {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Transfer Bank' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Status</span>
                        @php
                            $paymentStatusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                            ];
                            
                            $paymentStatusText = [
                                'pending' => $order->payment_method === 'cod' ? 'Cash Not Collected' : 'Pending',
                                'paid' => $order->payment_method === 'cod' ? 'Cash Collected' : 'Paid',
                                'failed' => 'Failed',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentStatusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $paymentStatusText[$order->payment_status] ?? ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Update Status -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Update Status</h2>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                                <select name="status" id="status" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->order_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                                <textarea name="notes" id="notes" rows="2" 
                                          placeholder="Add a note about this status change..."
                                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Payment Status -->
            @if($order->payment_method === 'transfer_bank' || $order->payment_method === 'cod')
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Update Payment</h2>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                                <select name="payment_status" id="payment_status" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>
                                        {{ $order->payment_method === 'cod' ? 'Cash Collected' : 'Paid' }}
                                    </option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                </select>
                            </div>
                            @if($order->payment_method === 'cod')
                            <p class="text-xs text-gray-500">
                                For COD orders, update to "Cash Collected" when payment is received during delivery.
                            </p>
                            @endif
                            <button type="submit" 
                                    class="w-full bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg font-medium">
                                Update Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
