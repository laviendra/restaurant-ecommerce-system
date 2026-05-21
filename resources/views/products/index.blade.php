@extends('layouts.app')

@section('title', 'Menu')

@section('content')
<div class="bg-white">
    <!-- Header -->
    <div class="bg-red-600 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-white">Our Menu</h1>
            <p class="mt-2 text-red-100">Discover our delicious menu items</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and Filter Section -->
        <div class="mb-8 bg-gray-50 rounded-lg p-4">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <label for="search" class="sr-only">Search products</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" 
                            value="{{ request('search') }}"
                            placeholder="Search menu items..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 pl-10 py-2 border">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="w-full md:w-48">
                    <label for="category" class="sr-only">Category</label>
                    <select name="category" id="category" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2 border">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition-colors">
                    Filter
                </button>

                @if(request('search') || request('category'))
                    <a href="{{ route('products.index') }}" 
                        class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300 transition-colors text-center">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Results Info -->
        @if(request('search'))
            <div class="mb-4">
                <p class="text-gray-600">
                    Showing results for "<span class="font-semibold">{{ request('search') }}</span>"
                    <span class="text-gray-400">({{ $products->total() }} items found)</span>
                </p>
            </div>
        @endif

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($products as $product)
                    @include('products.partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="text-red-600 hover:text-red-500">
                        View all products &rarr;
                    </a>
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

    // Show toast notification
    function showToast(message, type = 'success', duration = 3000) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');
        const toastContainer = toast.querySelector('div');
        
        // Set message
        toastMessage.textContent = message;
        
        // Set color based on type
        if (type === 'success') {
            toastContainer.className = 'bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg';
        } else if (type === 'error') {
            toastContainer.className = 'bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg';
        } else {
            toastContainer.className = 'bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg';
        }
        
        // Show toast
        toast.classList.remove('translate-y-full', 'opacity-0');
        
        // Hide after duration
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
        }, duration);
    }

    // Add to cart functionality with CSRF retry
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
