{{-- Product Image Gallery Component --}}
<div class="space-y-4">
    <!-- Main Image -->
    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden relative" id="main-image-container">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" 
                alt="{{ $product->name }}"
                id="main-image"
                class="w-full h-full object-cover cursor-zoom-in"
                onclick="openLightbox(this.src)">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <!-- Badges -->
        @if(!$product->is_available)
            <div class="absolute top-4 right-4">
                <span class="bg-gray-800 text-white text-sm font-semibold px-3 py-1 rounded">
                    Unavailable
                </span>
            </div>
        @endif

        @if($product->is_featured)
            <div class="absolute top-4 left-4">
                <span class="bg-yellow-500 text-white text-sm font-semibold px-3 py-1 rounded">
                    Featured
                </span>
            </div>
        @endif
    </div>

    <!-- Thumbnail Gallery -->
    @if($product->images->count() > 0 || $product->image)
        <div class="grid grid-cols-5 gap-2">
            <!-- Main image thumbnail -->
            @if($product->image)
                <button type="button" 
                    class="aspect-square bg-gray-100 rounded-md overflow-hidden border-2 border-red-500 thumbnail-btn"
                    onclick="changeMainImage('{{ asset('storage/' . $product->image) }}', this)">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                        alt="{{ $product->name }}"
                        class="w-full h-full object-cover">
                </button>
            @endif

            <!-- Additional images -->
            @foreach($product->images as $image)
                <button type="button" 
                    class="aspect-square bg-gray-100 rounded-md overflow-hidden border-2 border-transparent hover:border-red-300 thumbnail-btn"
                    onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                        alt="{{ $product->name }} - Image {{ $loop->iteration + 1 }}"
                        class="w-full h-full object-cover">
                </button>
            @endforeach
        </div>
    @endif
</div>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center" onclick="closeLightbox()">
    <button type="button" class="absolute top-4 right-4 text-white hover:text-gray-300" onclick="closeLightbox()">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
</div>

@push('scripts')
<script>
    function changeMainImage(src, clickedBtn) {
        document.getElementById('main-image').src = src;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail-btn').forEach(btn => {
            btn.classList.remove('border-red-500');
            btn.classList.add('border-transparent');
        });
        clickedBtn.classList.remove('border-transparent');
        clickedBtn.classList.add('border-red-500');
    }

    function openLightbox(src) {
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        lightboxImage.src = src;
        lightbox.classList.remove('hidden');
        lightbox.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Close lightbox on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
</script>
@endpush
