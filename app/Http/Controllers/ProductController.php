<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with pagination, search, and category filter.
     * Requirements: 2.1, 2.3, 2.4, 11.1, 11.2
     */
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images']);

        // Search functionality (Requirement 2.4)
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        // Category filter (Requirement 11.2)
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Get products with pagination (12 per page)
        $products = $query->orderBy('name')->paginate(12)->withQueryString();

        // Get all categories for filter dropdown (Requirement 11.1)
        $categories = Category::orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product with image gallery.
     * Requirements: 2.2, 2.3, 17.2
     */
    public function show(Product $product): View
    {
        // Load product with category and images for gallery
        $product->load(['category', 'images']);

        // Get related products from same category (excluding current product)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Search products by name.
     * Requirements: 2.4
     */
    public function search(Request $request): View
    {
        $searchTerm = $request->input('q', '');
        
        $query = Product::with(['category', 'images']);

        if (!empty($searchTerm)) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'searchTerm' => $searchTerm,
        ]);
    }
}
