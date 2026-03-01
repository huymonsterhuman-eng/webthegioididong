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
        // Get parent categories for the homepage blocks
        $categories = Category::whereNull('parent_id')
            ->orderBy('id')->get();

        // Load some flash sale or hot products (dummy logic: highest views or something)
        $hotProducts = Product::with(['category', 'brand'])
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        // Fetch a few products per category to display
        $categoryProducts = [];
        foreach ($categories as $cat) {
            $categoryProducts[$cat->id] = Product::with(['category', 'brand'])
                ->where('category_id', $cat->id)
                ->inRandomOrder()
                ->take(10)
                ->get();
        }

        // Load some posts
        $posts = Post::orderBy('date', 'desc')->take(3)->get();

        return view('home', compact('categories', 'hotProducts', 'categoryProducts', 'posts'));
    }
}
