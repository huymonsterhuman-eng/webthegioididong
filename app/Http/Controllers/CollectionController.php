<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Brand;

class CollectionController extends Controller
{
    public function show(Request $request, $slug)
    {
        $collection = Collection::with('children')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $collectionIds = [$collection->id];
        if ($collection->children->isNotEmpty()) {
            $collectionIds = array_merge($collectionIds, $collection->children->pluck('id')->toArray());
        }

        $query = \App\Models\Product::with(['brand', 'primaryImage'])->whereHas('collections', function ($q) use ($collectionIds) {
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
