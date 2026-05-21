@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="py-8 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-yellow-600 hover:text-yellow-700 flex items-center mb-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Products
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Product: {{ $product->name }}</h1>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                       placeholder="e.g., Big Mac">
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category_id" id="category_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Price -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rp) *</label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0" step="100"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                       placeholder="e.g., 50000">
            </div>

            <!-- Description -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                          class="w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                          placeholder="Product description...">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Current Main Image -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Current Main Image</label>
                @if($product->image)
                <div class="mb-2">
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-32 w-32 object-cover rounded-lg">
                </div>
                @else
                <p class="text-sm text-gray-500 mb-2">No image uploaded</p>
                @endif
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Replace Main Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-yellow-500 focus:border-yellow-500">
                <p class="mt-1 text-sm text-gray-500">Max 2MB. Leave empty to keep current image.</p>
                <div id="imagePreview" class="mt-2 hidden">
                    <img id="previewImg" class="h-32 w-32 object-cover rounded-lg" src="" alt="Preview">
                </div>
            </div>

            <!-- Current Gallery Images -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Gallery Images</label>
                @if($product->images->count() > 0)
                <div class="flex flex-wrap gap-4 mb-4">
                    @foreach($product->images as $image)
                    <div class="relative">
                        <img src="{{ Storage::url($image->image_path) }}" alt="Gallery" class="h-24 w-24 object-cover rounded-lg">
                        <label class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 cursor-pointer hover:bg-red-600">
                            <input type="checkbox" name="remove_gallery[]" value="{{ $image->id }}" class="sr-only">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </label>
                    </div>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mb-2">Click the X to mark images for removal</p>
                @else
                <p class="text-sm text-gray-500 mb-2">No gallery images</p>
                @endif

                <label for="gallery" class="block text-sm font-medium text-gray-700 mb-1">Add Gallery Images</label>
                <input type="file" name="gallery[]" id="gallery" accept="image/*" multiple
                       class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-yellow-500 focus:border-yellow-500">
                <p class="mt-1 text-sm text-gray-500">Max {{ 5 - $product->images->count() }} more images, 2MB each.</p>
                <div id="galleryPreview" class="mt-2 flex flex-wrap gap-2"></div>
            </div>

            <!-- Availability -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">Available for purchase</span>
                </label>
            </div>

            <!-- Featured -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500">
                    <span class="ml-2 text-sm text-gray-700">Featured product</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('admin.products.index') }}" 
               class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-md">
                Cancel
            </a>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-md">
                Update Product
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Main image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.classList.add('hidden');
        }
    });

    // Gallery preview
    document.getElementById('gallery').addEventListener('change', function(e) {
        const preview = document.getElementById('galleryPreview');
        preview.innerHTML = '';
        if (e.target.files) {
            Array.from(e.target.files).slice(0, 5).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-20 w-20 object-cover rounded-lg';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    });

    // Toggle remove gallery image styling
    document.querySelectorAll('input[name="remove_gallery[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const container = this.closest('.relative');
            const img = container.querySelector('img');
            if (this.checked) {
                img.classList.add('opacity-50', 'ring-2', 'ring-red-500');
            } else {
                img.classList.remove('opacity-50', 'ring-2', 'ring-red-500');
            }
        });
    });
</script>
@endpush
@endsection
