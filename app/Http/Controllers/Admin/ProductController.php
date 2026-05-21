<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with pagination.
     * Requirements: 6.1, 19.3
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Filter by availability
        if ($request->filled('availability')) {
            $query->where('is_available', $request->input('availability') === 'available');
        }

        // Filter by featured
        if ($request->filled('featured')) {
            $query->where('is_featured', $request->input('featured') === 'yes');
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     * Requirements: 6.2, 11.3, 17.1
     */
    public function create(): View
    {
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }


    /**
     * Store a newly created product in storage.
     * Requirements: 6.2, 11.3, 17.1
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle main image upload
        $imagePath = $request->file('image')->store('products', 'public');

        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'image' => $imagePath,
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
        ]);

        // Handle gallery images upload (up to 5 images)
        if ($request->hasFile('gallery')) {
            $sortOrder = 1;
            foreach (array_slice($request->file('gallery'), 0, 5) as $galleryImage) {
                $galleryPath = $galleryImage->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $galleryPath,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     * Requirements: 6.3, 11.3, 17.1
     */
    public function edit(Product $product): View
    {
        $product->load('images');
        $categories = Category::orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }


    /**
     * Update the specified product in storage.
     * Requirements: 6.3, 11.3, 17.1
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_gallery' => 'nullable|array',
            'remove_gallery.*' => 'exists:product_images,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
        ];

        // Handle main image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $updateData['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($updateData);

        // Remove selected gallery images
        if ($request->filled('remove_gallery')) {
            $imagesToRemove = ProductImage::whereIn('id', $request->input('remove_gallery'))
                ->where('product_id', $product->id)
                ->get();
            
            foreach ($imagesToRemove as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Handle new gallery images upload
        if ($request->hasFile('gallery')) {
            $currentCount = $product->images()->count();
            $maxNew = 5 - $currentCount;
            $sortOrder = $product->images()->max('sort_order') ?? 0;
            
            foreach (array_slice($request->file('gallery'), 0, $maxNew) as $galleryImage) {
                $galleryPath = $galleryImage->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $galleryPath,
                    'sort_order' => ++$sortOrder,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }


    /**
     * Remove the specified product from storage.
     * Requirements: 6.5
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Delete main image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete gallery images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product availability status.
     * Requirements: 6.4
     */
    public function toggleAvailability(Product $product): RedirectResponse
    {
        $product->update([
            'is_available' => !$product->is_available,
        ]);

        $status = $product->is_available ? 'available' : 'unavailable';
        return redirect()->back()
            ->with('success', "Product marked as {$status}.");
    }

    /**
     * Toggle product featured status.
     * Requirements: 19.1
     */
    public function toggleFeatured(Product $product): RedirectResponse
    {
        $product->update([
            'is_featured' => !$product->is_featured,
        ]);

        $status = $product->is_featured ? 'featured' : 'not featured';
        return redirect()->back()
            ->with('success', "Product marked as {$status}.");
    }
}
