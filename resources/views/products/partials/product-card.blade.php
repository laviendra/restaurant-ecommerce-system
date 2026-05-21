{{-- Product Card Component --}}
<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
    <a href="{{ route('products.show', $product) }}" class="block">
        <!-- Product Image -->
        <div class="relative aspect-square bg-gray-100">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            <!-- Availability Badge -->
            @if(!$product->is_available)
                <div class="absolute top-2 right-2">
                    <span class="bg-gray-800 text-white text-xs font-semibold px-2 py-1 rounded">
                        Unavailable
                    </span>
                </div>
            @endif

            <!-- Featured Badge -->
            @if($product->is_featured)
                <div class="absolute top-2 left-2">
                    <span class="bg-yellow-500 text-white text-xs font-semibold px-2 py-1 rounded">
                        Featured
                    </span>
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="p-4">
            <!-- Category -->
            @if($product->category)
                <p class="text-xs text-red-600 font-medium uppercase tracking-wide mb-1">
                    {{ $product->category->name }}
                </p>
            @endif

            <!-- Name -->
            <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1">
                {{ $product->name }}
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-500 mb-3 line-clamp-2">
                {{ $product->description }}
            </p>

            <!-- Price and Action -->
            <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-red-600">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>

                @if($product->is_available)
                    <span class="text-sm text-green-600 font-medium">In Stock</span>
                @else
                    <span class="text-sm text-gray-400 font-medium">Out of Stock</span>
                @endif
            </div>
        </div>
    </a>

    <!-- Add to Cart Button -->
    <div class="px-4 pb-4">
        @if($product->is_available)
            <button type="button" 
                class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 transition-colors font-medium add-to-cart-btn"
                data-product-id="{{ $product->id }}">
                Add to Cart
            </button>
        @else
            <button type="button" 
                class="w-full bg-gray-300 text-gray-500 py-2 px-4 rounded-md cursor-not-allowed font-medium" 
                disabled>
                Unavailable
            </button>
        @endif
    </div>
</div>
