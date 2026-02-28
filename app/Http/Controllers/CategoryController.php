<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;

class CategoryController extends Controller
{
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $query = Product::with('brand')->where('category_id', $category->id);

        if ($request->has('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->has('sort')) {
            if ($request->sort === 'price_asc')
                $query->orderBy('sale_price', 'asc');
            if ($request->sort === 'price_desc')
                $query->orderBy('sale_price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(20);
        $brands = Brand::has('products')->take(10)->get();

        return view('category', compact('category', 'products', 'brands'));
    }
}
