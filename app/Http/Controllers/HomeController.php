<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        // Get collections for the homepage blocks
        $collections = \App\Models\Collection::where('is_active', true)
            ->orderBy('sort_order')->get();

        // Load some flash sale or hot products (dummy logic: highest views or something)
        $hotProducts = Product::with(['category', 'brand', 'primaryImage'])
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        // Fetch a few products per collection to display
        $collectionProducts = [];
        foreach ($collections as $col) {
            /** @var \App\Models\Collection $col */
            $collectionProducts[$col->id] = $col->products()->with(['category', 'brand', 'primaryImage'])
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        // Load some published posts
        $posts = Post::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Load active vouchers
        $vouchers = \App\Models\Voucher::where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Load active banners
        $heroBanners = \App\Models\Banner::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('home', compact('collections', 'hotProducts', 'collectionProducts', 'posts', 'vouchers', 'heroBanners'));
    }
}
