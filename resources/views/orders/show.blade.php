@extends('layouts.app')

@section('title', 'Order ' . $order->order_number)

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <a href="{{ route('orders.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Orders
        </a>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">{{ $order->order_number }}</h1>
                    <p class="text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="flex flex-col sm:items-end gap-2">
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-blue-100 text-blue-800',
                            'processing' => 'bg-indigo-100 text-indigo-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-teal-100 text-teal-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                    @if($order->order_status === 'pending')
                    <form action="{{ route('orders.cancel', $order) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            Cancel Order
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Order Info Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Payment Method</p>
                    <p class="font-semibold text-gray-900">
                        {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Bank Transfer' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Payment Status</p>
                    <p class="font-semibold 
                        {{ $order->payment_status === 'paid' ? 'text-green-600' : ($order->payment_status === 'pending' ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ ucfirst($order->payment_status) }}
                    </p>
                </div>
                @if($order->invoice)
                <div>
                    <p class="text-sm text-gray-500">Invoice Number</p>
                    <p class="font-semibold text-gray-900">{{ $order->invoice->invoice_number }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500">Total Amount</p>
                    <p class="font-bold text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Delivery Information -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-500">Delivery Address</p>
                    <p class="text-gray-900">{{ $order->delivery_address }}</p>
                </div>
                @if($order->notes)
                <div>
                    <p class="text-sm text-gray-500">Order Notes</p>
                    <p class="text-gray-900">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-start py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                        <p class="text-sm text-gray-500">
                            {{ $item->quantity }} × Rp {{ number_format($item->product_price, 0, ',', '.') }}
                        </p>
                        @if($item->notes)
                        <p class="text-sm text-gray-500 italic mt-1">
                            <span class="font-medium">Note:</span> {{ $item->notes }}
                        </p>
                        @endif
                    </div>
                    <span class="font-medium text-gray-900">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center text-lg font-bold">
                    <span>Total</span>
                    <span class="text-red-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Delivery Tracking & Confirmation -->
        @if($order->shipped_at || $order->delivered_at || $order->order_status === 'shipped')
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Delivery Tracking</h2>
            
            @if($order->shipped_at)
            <div class="mb-4 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl shadow-sm">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4-4m-4 4l4 4"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-blue-800">🚚 Order On The Way!</span>
                        <p class="text-sm text-blue-700 mt-1">Your McDonald's order is being delivered</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Shipped on:</span>
                        <span class="font-medium text-gray-900">{{ $order->shipped_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                
                <div class="mt-4 flex items-center justify-center text-2xl">
                    🍔 ➡️ 🚚 ➡️ 🏠
                </div>
            </div>
            @endif
            
            @if($order->delivered_at)
            <div class="mb-4 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl shadow-sm">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold text-green-800">🎉 Order Delivered Successfully!</span>
                        <p class="text-sm text-green-700 mt-1">Thank you for choosing McDonald's</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 border border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Delivered on:</span>
                        <span class="font-medium text-gray-900">{{ $order->delivered_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($order->delivery_notes)
                    <div class="mt-3 pt-3 border-t border-green-100">
                        <p class="text-sm text-gray-600 mb-1"><strong>Your Feedback:</strong></p>
                        <p class="text-sm text-gray-800 bg-green-50 p-3 rounded-lg italic">"{{ $order->delivery_notes }}"</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 flex items-center justify-center text-2xl">
                    🍔 🍟 🥤 ⭐ ⭐ ⭐ ⭐ ⭐
                </div>
            </div>
            @endif
            
            <!-- Delivery Confirmation Form -->
            @if($order->order_status === 'shipped' && !$order->delivered_at)
            <div class="mt-4 p-6 bg-gradient-to-r from-yellow-50 to-red-50 border-2 border-yellow-200 rounded-xl shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-1a1 1 0 011-1h2a1 1 0 011 1v1a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">🍟 Your Order Has Arrived!</h3>
                        <p class="text-sm text-gray-600">Please confirm delivery to complete your McDonald's experience</p>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg p-4 mb-4 border border-gray-200">
                    <p class="text-gray-700 mb-2">
                        <span class="font-medium">📦 Order Status:</span> Ready for delivery confirmation
                    </p>
                    <p class="text-gray-700">
                        <span class="font-medium">🚚 Shipped:</span> {{ $order->shipped_at->format('d M Y, H:i') }}
                    </p>
                </div>
                
                <form id="delivery-confirmation-form" action="{{ route('orders.confirm-delivery', $order) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1.586l-4.707 4.707z"/>
                                </svg>
                                How was your McDonald's experience? (Optional)
                            </span>
                        </label>
                        <textarea name="delivery_notes" id="delivery_notes" rows="3" 
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 resize-none"
                                  placeholder="Tell us about your delivery experience, food quality, or any feedback..."></textarea>
                    </div>
                    
                    <button type="button" id="confirm-delivery-btn"
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-lg font-bold text-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        ✅ Yes, I Received My Order!
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endif

        <!-- Status Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Status History</h2>
            @if($order->statusHistories->count() > 0)
            <div class="relative">
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                <div class="space-y-6">
                    @foreach($order->statusHistories as $history)
                    <div class="relative flex items-start">
                        @php
                            $dotColors = [
                                'pending' => 'bg-yellow-500',
                                'confirmed' => 'bg-blue-500',
                                'processing' => 'bg-indigo-500',
                                'shipped' => 'bg-purple-500',
                                'delivered' => 'bg-teal-500',
                                'completed' => 'bg-green-500',
                                'cancelled' => 'bg-red-500',
                            ];
                        @endphp
                        <div class="absolute left-2 w-4 h-4 rounded-full {{ $dotColors[$history->status] ?? 'bg-gray-500' }} border-2 border-white"></div>
                        <div class="ml-10">
                            <p class="font-medium text-gray-900">{{ ucfirst($history->status) }}</p>
                            @if($history->notes)
                            <p class="text-sm text-gray-600">{{ $history->notes }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $history->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <p class="text-gray-500">No status history available.</p>
            @endif
        </div>

        <!-- Payment Proof (if Transfer Bank) -->
        @if($order->payment_method === 'transfer_bank' && $order->payment_proof)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Proof</h2>
            <img src="{{ asset('storage/' . $order->payment_proof) }}" 
                 alt="Payment Proof" 
                 class="max-w-md rounded-lg border border-gray-200 cursor-pointer hover:opacity-90"
                 onclick="window.open(this.src, '_blank')">
            <p class="text-sm text-gray-500 mt-2">Click image to view full size</p>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">
            @if($order->invoice)
            <a href="{{ route('invoices.show', $order) }}" 
               class="flex-1 bg-red-600 text-white py-3 px-6 rounded-lg font-semibold text-center hover:bg-red-700 transition">
                View Invoice
            </a>
            <a href="{{ route('invoices.print', $order) }}" 
               target="_blank"
               class="flex-1 bg-white text-gray-700 py-3 px-6 rounded-lg font-semibold text-center border border-gray-300 hover:bg-gray-50 transition">
                Print Invoice
            </a>
            @endif
            <a href="{{ route('products.index') }}" 
               class="flex-1 bg-white text-gray-700 py-3 px-6 rounded-lg font-semibold text-center border border-gray-300 hover:bg-gray-50 transition">
                Continue Shopping
            </a>
        </div>
    </div>
</div>

<!-- McDonald's Style Delivery Confirmation Modal -->
<div id="delivery-confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-red-600 to-yellow-500 rounded-t-2xl p-6 text-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl">🍟</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Delivery Confirmation</h3>
            <p class="text-red-100 text-sm">McDonald's wants to make sure you got your order!</p>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">Did you receive your order?</h4>
                <p class="text-gray-600 text-sm">Please confirm that your McDonald's order has been delivered successfully.</p>
            </div>
            
            <!-- Order Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Order Number:</span>
                    <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-2">
                    <span class="text-gray-600">Shipped:</span>
                    <span class="font-medium text-gray-900">{{ $order->shipped_at ? $order->shipped_at->format('d M Y, H:i') : 'N/A' }}</span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button id="cancel-confirmation" 
                        class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium hover:bg-gray-300 transition">
                    Not Yet
                </button>
                <button id="confirm-yes" 
                        class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-4 rounded-lg font-bold hover:from-green-700 hover:to-green-800 transition">
                    ✅ Yes, Received!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Animation Modal -->
<div id="success-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full transform transition-all text-center p-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Thank You! 🎉</h3>
        <p class="text-gray-600 mb-4">Your delivery has been confirmed successfully.</p>
        <div class="text-4xl mb-4">🍔🍟🥤</div>
        <p class="text-sm text-gray-500">Submitting confirmation...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('confirm-delivery-btn');
    const modal = document.getElementById('delivery-confirmation-modal');
    const successModal = document.getElementById('success-modal');
    const cancelBtn = document.getElementById('cancel-confirmation');
    const confirmYesBtn = document.getElementById('confirm-yes');
    const form = document.getElementById('delivery-confirmation-form');
    
    if (confirmBtn && modal) {
        // Show modal when confirm button is clicked
        confirmBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
        
        // Hide modal when cancel is clicked
        cancelBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
        
        // Hide modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
        
        // Handle confirmation
        confirmYesBtn.addEventListener('click', function() {
            // Hide confirmation modal
            modal.classList.add('hidden');
            
            // Show success modal
            successModal.classList.remove('hidden');
            
            // Submit form after animation
            setTimeout(function() {
                form.submit();
            }, 2000);
        });
    }
});
</script>
@endpush
