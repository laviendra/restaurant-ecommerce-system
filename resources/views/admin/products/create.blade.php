@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<!-- Header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('admin.products.index') }}" class="text-mcd-red hover:text-mcd-dark-red flex items-center mb-2 text-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Products
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
        <p class="text-gray-600">Create a new menu item for your restaurant</p>
    </div>
</div>

<!-- Form -->
<div class="bg-white rounded-xl shadow-sm">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-mcd-red bg-opacity-10 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-mcd-red"></i>
                    </div>
                    Basic Information
                </h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red @error('name') border-red-500 @enderror"
                               placeholder="e.g., Big Mac Deluxe" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select name="category_id" id="category_id" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red @error('category_id') border-red-500 @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Price (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" 
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red @error('price') border-red-500 @enderror"
                                   placeholder="50000" min="0" step="1000" required>
                        </div>
                        @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Toggles -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_available" id="is_available" value="1" 
                                   class="h-4 w-4 text-mcd-red focus:ring-mcd-red border-gray-300 rounded" 
                                   {{ old('is_available', true) ? 'checked' : '' }}>
                            <label for="is_available" class="ml-3 text-sm font-medium text-gray-700">
                                Available for order
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" 
                                   class="h-4 w-4 text-mcd-yellow focus:ring-mcd-yellow border-gray-300 rounded" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label for="is_featured" class="ml-3 text-sm font-medium text-gray-700">
                                Featured product (show on homepage)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red @error('description') border-red-500 @enderror"
                              placeholder="Describe your product, ingredients, and what makes it special...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Images -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-mcd-yellow bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-images text-mcd-yellow"></i>
                    </div>
                    Product Images
                </h3>

                <!-- Main Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Main Product Image <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-mcd-red transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-mcd-red hover:text-mcd-dark-red focus-within:outline-none">
                                    <span>Upload main image</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                        </div>
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImg" class="h-32 w-32 object-cover rounded-lg mx-auto shadow-md" src="" alt="Preview">
                    </div>
                    @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gallery Images -->
                <div>
                    <label for="gallery" class="block text-sm font-medium text-gray-700 mb-2">
                        Gallery Images (Optional)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-mcd-yellow transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="gallery" class="relative cursor-pointer bg-white rounded-md font-medium text-mcd-yellow hover:text-yellow-600 focus-within:outline-none">
                                    <span>Upload gallery images</span>
                                    <input id="gallery" name="gallery[]" type="file" class="sr-only" accept="image/*" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">Multiple images, up to 5 total. PNG, JPG, GIF up to 2MB each</p>
                        </div>
                    </div>
                    <div id="galleryPreview" class="mt-4 flex flex-wrap gap-2"></div>
                    @error('gallery.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl flex justify-end space-x-3">
            <a href="{{ route('admin.products.index') }}" 
               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-mcd-red to-mcd-dark-red text-white rounded-lg hover:from-mcd-dark-red hover:to-red-800 font-medium transition-all transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>
                Create Product
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
                img.className = 'h-20 w-20 object-cover rounded-lg shadow-md';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
        
        if (e.target.files.length > 5) {
            alert('Maximum 5 gallery images allowed. Only the first 5 will be uploaded.');
        }
    }
});
</script>
@endpush
@endsection
