<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->with('category')
            ->take(6)
            ->get();
            
        $categories = Category::where('status', 'active')
            ->orderBy('sort_order')
            ->take(4)
            ->get();
            
        return view('home', compact('featuredProducts', 'categories'));
    }
}
