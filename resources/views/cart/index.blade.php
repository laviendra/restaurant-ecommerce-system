@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

        @if($cartItems->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-xl font-semibold text-gray-700 mb-2">Your cart is empty</h2>
                <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition duration-200">
                    Browse Menu
                </a>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Cart Items -->
                <div id="cart-items" class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <div class="cart-item p-6" data-item-id="{{ $item->id }}">
                            <div class="flex items-start space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0 w-24 h-24">
                                    @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                    <p class="text-yellow-600 font-medium">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    
                                    <!-- Notes Input -->
                                    <div class="mt-2">
                                        <input type="text" 
                                               class="item-notes w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-yellow-500 focus:ring-yellow-500" 
                                               placeholder="Add special request (e.g., no onions)"
                                               value="{{ $item->notes }}"
                                               data-item-id="{{ $item->id }}">
                                    </div>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center space-x-3">
                                    <button type="button" 
                                            class="quantity-btn decrease w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center transition duration-200"
                                            data-item-id="{{ $item->id }}"
                                            data-action="decrease">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    <span class="item-quantity text-lg font-semibold w-8 text-center">{{ $item->quantity }}</span>
                                    <button type="button" 
                                            class="quantity-btn increase w-8 h-8 rounded-full bg-yellow-500 hover:bg-yellow-600 text-white flex items-center justify-center transition duration-200"
                                            data-item-id="{{ $item->id }}"
                                            data-action="increase">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Subtotal & Remove -->
                                <div class="text-right">
                                    <p class="item-subtotal text-lg font-bold text-gray-900">Rp {{ number_format($item->getSubtotal(), 0, ',', '.') }}</p>
                                    <button type="button" 
                                            class="remove-btn mt-2 text-red-500 hover:text-red-700 text-sm font-medium transition duration-200"
                                            data-item-id="{{ $item->id }}">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold text-gray-700">Total</span>
                        <span id="cart-total" class="text-2xl font-bold text-yellow-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('products.index') }}" class="flex-1 text-center px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition duration-200">
                            Continue Shopping
                        </a>
                        <a href="{{ route('checkout.index') }}" id="checkout-btn" class="flex-1 text-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg transition duration-200">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 transform translate-y-full opacity-0 transition-all duration-300 z-50">
    <div class="bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg">
        <span id="toast-message"></span>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Show toast notification
    function showToast(message, duration = 3000) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        toastMessage.textContent = message;
        toast.classList.remove('translate-y-full', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
        }, duration);
    }

    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Update cart item quantity
    async function updateQuantity(itemId, quantity, notes = null) {
        try {
            const response = await fetch(`/cart/update/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ quantity, notes }),
            });

            const data = await response.json();

            if (data.success) {
                if (data.data.removed) {
                    // Remove item from DOM
                    const itemElement = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
                    if (itemElement) {
                        itemElement.remove();
                    }
                    showToast('Item removed from cart');
                    
                    // Check if cart is empty
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        location.reload();
                    }
                } else {
                    // Update quantity and subtotal
                    const itemElement = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
                    if (itemElement) {
                        itemElement.querySelector('.item-quantity').textContent = data.data.item.quantity;
                        itemElement.querySelector('.item-subtotal').textContent = formatCurrency(data.data.item.subtotal);
                    }
                }

                // Update total
                document.getElementById('cart-total').textContent = formatCurrency(data.data.cart_total);
            } else {
                showToast(data.message || 'Error updating cart');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error updating cart');
        }
    }

    // Remove item from cart
    async function removeItem(itemId) {
        try {
            const response = await fetch(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                const itemElement = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
                if (itemElement) {
                    itemElement.remove();
                }
                
                document.getElementById('cart-total').textContent = formatCurrency(data.data.cart_total);
                showToast('Item removed from cart');

                // Check if cart is empty
                const remainingItems = document.querySelectorAll('.cart-item');
                if (remainingItems.length === 0) {
                    location.reload();
                }
            } else {
                showToast(data.message || 'Error removing item');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error removing item');
        }
    }

    // Quantity button click handlers
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            const action = this.dataset.action;
            const itemElement = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
            const quantityElement = itemElement.querySelector('.item-quantity');
            let currentQuantity = parseInt(quantityElement.textContent);

            if (action === 'increase') {
                currentQuantity++;
            } else if (action === 'decrease') {
                currentQuantity--;
            }

            updateQuantity(itemId, currentQuantity);
        });
    });

    // Remove button click handlers
    document.querySelectorAll('.remove-btn').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;
            if (confirm('Are you sure you want to remove this item?')) {
                removeItem(itemId);
            }
        });
    });

    // Notes input change handlers (debounced)
    let notesTimeout;
    document.querySelectorAll('.item-notes').forEach(input => {
        input.addEventListener('input', function() {
            const itemId = this.dataset.itemId;
            const notes = this.value;
            const itemElement = document.querySelector(`.cart-item[data-item-id="${itemId}"]`);
            const quantity = parseInt(itemElement.querySelector('.item-quantity').textContent);

            clearTimeout(notesTimeout);
            notesTimeout = setTimeout(() => {
                updateQuantity(itemId, quantity, notes);
            }, 500);
        });
    });
});
</script>
@endpush
