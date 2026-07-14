<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Brand;

/**
 * Controller trang bộ sưu tập — liệt kê sản phẩm theo collection và các collection con.
 * Tương tự CategoryController nhưng dùng quan hệ N:M (collection_product).
 */
class CollectionController extends Controller
{
    /** Trang danh sách sản phẩm trong 1 bộ sưu tập (kèm bộ sưu tập con) */
    public function show(Request $request, $slug)
    {
        $collection = Collection::with('children')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $collectionIds = [$collection->id];
        if ($collection->children->isNotEmpty()) {
            $collectionIds = array_merge($collectionIds, $collection->children->pluck('id')->toArray());
        }

        $notHidden = fn($q) => $q->where('is_hidden', false);
        $query = \App\Models\Product::active()->with(['brand', 'primaryImage'])
            ->withCount(['reviews as reviews_count' => $notHidden])
            ->withAvg(['reviews as avg_rating' => $notHidden], 'rating')
            ->whereHas('collections', function ($q) use ($collectionIds) {
                $q->whereIn('collections.id', $collectionIds);
            });

        if ($request->has('brand')) {
            $brand = Brand::where('slug', $request->brand)->first();
            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($request->has('sort')) {
            if ($request->sort === 'price_asc')
                $query->orderBy('sale_price', 'asc');
            elseif ($request->sort === 'price_desc')
                $query->orderBy('sale_price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(20);
        $brands = Brand::has('products')->take(10)->get();

        return view('collection', compact('collection', 'products', 'brands'));
    }
}
