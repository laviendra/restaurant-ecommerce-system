@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<!-- Header -->
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Product Management</h1>
        <p class="text-gray-600">Manage your restaurant menu items</p>
    </div>
    <a href="{{ route('admin.products.create') }}" 
       class="bg-gradient-to-r from-mcd-red to-mcd-dark-red text-white px-6 py-3 rounded-lg flex items-center hover:from-mcd-dark-red hover:to-red-800 transition-all transform hover:scale-105 shadow-lg">
        <i class="fas fa-plus mr-2"></i>
        Add New Product
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-mcd-red">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-hamburger text-mcd-red text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Products</p>
                <p class="text-2xl font-bold text-gray-900">{{ $products->total() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Available</p>
                <p class="text-2xl font-bold text-gray-900">{{ $products->where('is_available', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-mcd-yellow">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-star text-mcd-yellow text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Featured</p>
                <p class="text-2xl font-bold text-gray-900">{{ $products->where('is_featured', true)->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-tags text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Categories</p>
                <p class="text-2xl font-bold text-gray-900">{{ $categories->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="mb-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-filter text-gray-600"></i>
        </div>
        Filter Products
    </h3>
    
    <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Product</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Product name..."
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red">
            </div>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
            <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
            <select name="availability" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red">
                <option value="">All Status</option>
                <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
            <select name="featured" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-mcd-red focus:border-mcd-red">
                <option value="">All Products</option>
                <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>Featured Only</option>
                <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>Not Featured</option>
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 bg-mcd-red hover:bg-mcd-dark-red text-white px-4 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-search mr-2"></i>
                Filter
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

<!-- Products Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Products List</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Featured</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16">
                                @if($product->image)
                                <img class="h-16 w-16 rounded-xl object-cover shadow-sm" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                @else
                                <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <i class="fas fa-hamburger text-gray-400 text-xl"></i>
                                </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $product->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('admin.products.toggle-availability', $product) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $product->is_available ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                <i class="fas fa-{{ $product->is_available ? 'check' : 'times' }} mr-1"></i>
                                {{ $product->is_available ? 'Available' : 'Unavailable' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('admin.products.toggle-featured', $product) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors {{ $product->is_featured ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                <i class="fas fa-{{ $product->is_featured ? 'star' : 'star-o' }} mr-1"></i>
                                {{ $product->is_featured ? 'Featured' : 'Not Featured' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="p-2 text-mcd-yellow hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Edit Product">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" title="Delete Product">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-hamburger text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                            <p class="text-gray-500 mb-4">Get started by creating your first menu item.</p>
                            <a href="{{ route('admin.products.create') }}" class="bg-mcd-red hover:bg-mcd-dark-red text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Add Your First Product
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
