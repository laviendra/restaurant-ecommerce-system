@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('cart.index') }}" class="text-red-600 hover:text-red-700 flex items-center mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Cart
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
        </div>

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-4">
                        @foreach($cartItems as $item)
                        <div class="flex justify-between items-start border-b border-gray-200 pb-4">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                @if($item->notes)
                                <p class="text-sm text-gray-500 italic mt-1">Note: {{ $item->notes }}</p>
                                @endif
                            </div>
                            <span class="font-medium text-gray-900">Rp {{ number_format($item->getSubtotal(), 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total</span>
                            <span class="text-red-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Delivery & Payment Form -->
                <div class="space-y-6">
                    <!-- Delivery Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Information</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Delivery Address <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    name="delivery_address" 
                                    id="delivery_address" 
                                    rows="3" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('delivery_address') border-red-500 @enderror"
                                    placeholder="Enter your complete delivery address"
                                    required
                                >{{ old('delivery_address', auth()->user()->address ?? '') }}</textarea>
                                @error('delivery_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Order Notes (Optional)
                                </label>
                                <textarea 
                                    name="notes" 
                                    id="notes" 
                                    rows="2" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                                    placeholder="Any special instructions for your order"
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Method</h2>
                        
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="cod" 
                                    class="h-4 w-4 text-red-600 focus:ring-red-500"
                                    {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}
                                >
                                <div class="ml-3">
                                    <span class="font-medium text-gray-900">Cash on Delivery (COD)</span>
                                    <p class="text-sm text-gray-500">Pay when your order arrives</p>
                                </div>
                            </label>
                            
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="transfer_bank" 
                                    class="h-4 w-4 text-red-600 focus:ring-red-500"
                                    {{ old('payment_method') === 'transfer_bank' ? 'checked' : '' }}
                                >
                                <div class="ml-3">
                                    <span class="font-medium text-gray-900">Bank Transfer</span>
                                    <p class="text-sm text-gray-500">Transfer to our bank account and upload proof</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-red-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                        Continue to Payment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
