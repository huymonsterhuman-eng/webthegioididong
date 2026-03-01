<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($slug, $id)
    {
        $product = Product::with(['category', 'brand'])->where('slug', $slug)->firstOrFail();

        // Increase views
        $product->increment('views');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }
}
