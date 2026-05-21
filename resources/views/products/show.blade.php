@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="bg-white">
    <!-- Breadcrumb -->
    <div class="bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-700">Home</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('products.index') }}" class="ml-2 text-gray-500 hover:text-gray-700">Menu</a>
                    </li>
                    @if($product->category)
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="ml-2 text-gray-500 hover:text-gray-700">
                            {{ $product->category->name }}
                        </a>
                    </li>
                    @endif
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-2 text-gray-900 font-medium">{{ $product->name }}</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
            <!-- Image Gallery -->
            <div class="mb-8 lg:mb-0">
                @include('products.partials.image-gallery', ['product' => $product])
            </div>

            <!-- Product Info -->
            <div class="lg:pl-8">
                <!-- Category -->
                @if($product->category)
                    <p class="text-sm text-red-600 font-medium uppercase tracking-wide mb-2">
                        {{ $product->category->name }}
                    </p>
                @endif

                <!-- Name -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <!-- Availability Status -->
                <div class="mb-4">
                    @if($product->is_available)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Available
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            Unavailable
                        </span>
                    @endif
                </div>

                <!-- Price -->
                <div class="mb-6">
                    <span class="text-3xl font-bold text-red-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Add to Cart Section -->
                @if($product->is_available)
                    <div class="border-t pt-6">
                        <form id="add-to-cart-form" class="space-y-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <div class="flex items-center space-x-3">
                                    <button type="button" id="decrease-qty" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="99"
                                        class="w-20 text-center border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <button type="button" id="increase-qty" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Special Request (Optional)</label>
                                <textarea name="notes" id="notes" rows="2" 
                                    placeholder="E.g., No pickles, extra sauce..."
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                            </div>

                            <!-- Add to Cart Button -->
                            <button type="submit" 
                                class="w-full bg-red-600 text-white py-3 px-6 rounded-md hover:bg-red-700 transition-colors font-semibold text-lg">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t pt-6">
                        <button type="button" 
                            class="w-full bg-gray-300 text-gray-500 py-3 px-6 rounded-md cursor-not-allowed font-semibold text-lg" 
                            disabled>
                            Currently Unavailable
                        </button>
                        <p class="mt-2 text-sm text-gray-500 text-center">This item is temporarily out of stock.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-16 border-t pt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">You May Also Like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('products.partials.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Function to refresh CSRF token
    function refreshCsrfToken() {
        fetch('/csrf-token', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.token) {
                csrfToken = data.token;
                document.querySelector('meta[name="csrf-token"]').content = csrfToken;
            }
        })
        .catch(error => console.log('CSRF token refresh failed:', error));
    }

    // Quantity controls
    document.getElementById('decrease-qty')?.addEventListener('click', function() {
        const input = document.getElementById('quantity');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
        }
    });

    document.getElementById('increase-qty')?.addEventListener('click', function() {
        const input = document.getElementById('quantity');
        if (input.value < 99) {
            input.value = parseInt(input.value) + 1;
        }
    });

    // Toast notification function
    function showToast(message, type = 'success', duration = 3000) {
        // Remove existing toast
        const existingToast = document.getElementById('toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create toast element
        const toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'fixed bottom-4 right-4 z-50 transform transition-all duration-300';
        
        const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
        
        toast.innerHTML = `
            <div class="${bgColor} text-white px-6 py-3 rounded-lg shadow-lg">
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 100);
        
        // Hide toast after duration
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }, duration);
    }

    // Add to cart form submission (main product)
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            const productId = this.querySelector('input[name="product_id"]').value;
            const quantity = this.querySelector('input[name="quantity"]').value;
            const notes = this.querySelector('textarea[name="notes"]').value;
            
            // Disable button and show loading
            submitButton.disabled = true;
            submitButton.textContent = 'Adding to Cart...';
            submitButton.classList.add('opacity-75');
            
            @auth
            function attemptAddToCart(retryCount = 0) {
                // Prepare form data
                const formData = new FormData();
                formData.append('quantity', quantity);
                if (notes.trim()) {
                    formData.append('notes', notes.trim());
                }
                
                fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    // Update CSRF token if provided in response
                    const newToken = response.headers.get('X-CSRF-TOKEN');
                    if (newToken) {
                        csrfToken = newToken;
                        document.querySelector('meta[name="csrf-token"]').content = csrfToken;
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('Product added to cart successfully!', 'success');
                        
                        // Update cart count in navigation if exists
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount && data.data.cart_count) {
                            cartCount.textContent = data.data.cart_count;
                        }
                        
                        // Reset form
                        document.getElementById('quantity').value = 1;
                        document.getElementById('notes').value = '';
                    } else {
                        // If CSRF token mismatch and haven't retried, refresh token and retry
                        if (data.message && data.message.includes('CSRF') && retryCount < 1) {
                            refreshCsrfToken();
                            setTimeout(() => attemptAddToCart(retryCount + 1), 500);
                            return;
                        }
                        showToast(data.message || 'Failed to add product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // If network error and haven't retried, try refreshing token
                    if (retryCount < 1) {
                        refreshCsrfToken();
                        setTimeout(() => attemptAddToCart(retryCount + 1), 500);
                        return;
                    }
                    showToast('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    // Re-enable button
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                    submitButton.classList.remove('opacity-75');
                });
            }
            
            attemptAddToCart();
            @else
            // Redirect to login if not authenticated
            showToast('Please login to add items to cart', 'error');
            setTimeout(() => {
                window.location.href = '{{ route("login") }}';
            }, 1500);
            @endauth
        });
    }
    
    // Add to cart functionality for related products (product cards) with CSRF retry
    document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const originalText = this.textContent;
            
            // Disable button and show loading
            this.disabled = true;
            this.textContent = 'Adding...';
            this.classList.add('opacity-75');
            
            @auth
            function attemptAddToCart(retryCount = 0) {
                fetch(`/cart/add/${productId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // Update CSRF token if provided in response
                    const newToken = response.headers.get('X-CSRF-TOKEN');
                    if (newToken) {
                        csrfToken = newToken;
                        document.querySelector('meta[name="csrf-token"]').content = csrfToken;
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast('Product added to cart successfully!', 'success');
                        
                        // Update cart count in navigation if exists
                        const cartCount = document.querySelector('.cart-count');
                        if (cartCount && data.data.cart_count) {
                            cartCount.textContent = data.data.cart_count;
                        }
                    } else {
                        // If CSRF token mismatch and haven't retried, refresh token and retry
                        if (data.message && data.message.includes('CSRF') && retryCount < 1) {
                            refreshCsrfToken();
                            setTimeout(() => attemptAddToCart(retryCount + 1), 500);
                            return;
                        }
                        showToast(data.message || 'Failed to add product to cart', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // If network error and haven't retried, try refreshing token
                    if (retryCount < 1) {
                        refreshCsrfToken();
                        setTimeout(() => attemptAddToCart(retryCount + 1), 500);
                        return;
                    }
                    showToast('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    // Re-enable button
                    button.disabled = false;
                    button.textContent = originalText;
                    button.classList.remove('opacity-75');
                });
            }
            
            attemptAddToCart();
            @else
            // Redirect to login if not authenticated
            showToast('Please login to add items to cart', 'error');
            setTimeout(() => {
                window.location.href = '{{ route("login") }}';
            }, 1500);
            @endauth
        });
    });
    
    // Refresh CSRF token every 10 minutes
    setInterval(refreshCsrfToken, 600000);
});
</script>
@endpush
@endsection
