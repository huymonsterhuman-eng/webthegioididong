<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

/**
 * Controller xử lý trang sản phẩm — chi tiết sản phẩm và tìm kiếm.
 * Chỉ trả về sản phẩm đang kinh doanh (is_active = true).
 */
class ProductController extends Controller
{
    /** Tìm kiếm sản phẩm theo từ khoá (name + description) */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::active()->with(['category', 'brand', 'primaryImage'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->paginate(20)
            ->withQueryString();

        return view('search', compact('products', 'query'));
    }

    /**
     * Trang chi tiết sản phẩm theo URL `/{categorySlug}/{productSlug}`.
     * Tự động tăng view counter và gợi ý sản phẩm liên quan cùng danh mục.
     */
    public function show($categorySlug, $productSlug)
    {
        $product = Product::active()->with(['category', 'brand', 'primaryImage'])->where('slug', $productSlug)->firstOrFail();

        // Increase views
        $product->increment('views');

        $relatedProducts = Product::active()->with(['category', 'brand', 'primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('product', compact('product', 'relatedProducts'));
    }
}
