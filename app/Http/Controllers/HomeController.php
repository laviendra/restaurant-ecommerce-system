<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page with featured products and McD information.
     * Requirements: 9.1, 19.2
     */
    public function index(): View
    {
        // Get featured products (Requirement 19.2)
        $featuredProducts = Product::with(['category', 'images'])
            ->featured()
            ->available()
            ->limit(8)
            ->get();

        return view('home', compact('featuredProducts'));
    }
}
