<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;

/**
 * Controller trang danh mục — liệt kê sản phẩm theo danh mục cha + danh mục con.
 * Hỗ trợ filter theo brand và sort theo giá.
 */
class CategoryController extends Controller
{
    /** Trang danh sách sản phẩm của 1 danh mục (kèm danh mục con) */
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $categoryIds = [$category->id];
        $categoryIds = array_merge($categoryIds, $category->children()->where('is_active', true)->pluck('id')->toArray());

        $query = Product::active()->with('brand')->whereIn('category_id', $categoryIds);

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
