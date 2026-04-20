<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use 

class ShopController extends Controller
{
   public function index(Request $request)
    {
        $query = Product::query()->where('status', 1);

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 📂 Category Filter
        if ($request->filled('category')) {
            $query->whereIn('category_id', $request->category);
        }

        // 🥚 Type Filter (egg / hen)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 💰 Price Range
        if ($request->filled('min_price')) {
            $query->where('base_price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('base_price', '<=', $request->max_price);
        }

        // 🔥 Sorting
        switch ($request->sort) {
            case 'low':
                $query->orderBy('base_price', 'asc');
                break;

            case 'high':
                $query->orderBy('base_price', 'desc');
                break;

            case 'latest':
                $query->latest();
                break;

            default:
                $query->latest();
                break;
        }

        // ⚡ Eager Load (performance)
        $products = $query->with('media')->paginate(9)->withQueryString();

        // 📂 Categories for sidebar
        $categories = Category::where('status', 1)->get();

        return view('custom.shop', compact('products', 'categories'));
    }


   public function show($slug)
{
    $product = Product::with('category')->where('slug', $slug)->firstOrFail();

    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->where('status', 1)
        ->latest()
        ->take(4)
        ->get();

    return view('custom.shop-detail', compact('product', 'relatedProducts'));
}
}