<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where(function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
        })
            ->paginate(20)
            ->withQueryString();

        return view('search', compact('products', 'query'));
    }

    public function show($categorySlug, $productSlug)
    {
        $product = Product::with(['category', 'brand'])->where('slug', $productSlug)->firstOrFail();

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
