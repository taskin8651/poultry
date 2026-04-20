<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\BulkPrice;
use App\Models\PriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::pluck('name', 'id');

        return view('admin.products.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'type' => 'required|in:egg,hen',
            'sale_type' => 'required|in:tray,piece,weight',
            'base_price' => 'required|numeric',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . time()),
            'category_id' => $request->category_id,
            'type' => $request->type,
            'sale_type' => $request->sale_type,
            'base_price' => $request->base_price,
            'stock' => $request->stock ?? 0,
            'description' => $request->description,
            'status' => 1,
        ]);

        // ✅ Price history (first entry)
        PriceHistory::create([
            'product_id' => $product->id,
            'price' => $request->base_price,
            'date' => now(),
        ]);

        // ✅ Tags
        $product->tags()->sync($request->tags ?? []);

        // ✅ Bulk pricing
        if ($request->bulk_qty) {
            foreach ($request->bulk_qty as $i => $qty) {
                if ($qty && $request->bulk_price[$i]) {
                    BulkPrice::create([
                        'product_id' => $product->id,
                        'min_qty' => $qty,
                        'price' => $request->bulk_price[$i],
                    ]);
                }
            }
        }

        // ✅ Thumbnail
        if ($request->hasFile('thumbnail')) {
            $product->addMediaFromRequest('thumbnail')
                ->toMediaCollection('product_thumbnail');
        }

        // ✅ Gallery
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $product->addMedia($img)
                    ->toMediaCollection('product_gallery');
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::pluck('name', 'id');
        $tags = Tag::pluck('name', 'id');

        $product->load('bulkPrices');

        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'type' => 'required|in:egg,hen',
            'sale_type' => 'required|in:tray,piece,weight',
            'base_price' => 'required|numeric',
        ]);

        // 🔥 check price change before update
        $oldPrice = $product->base_price;

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name . '-' . time()),
            'category_id' => $request->category_id,
            'type' => $request->type,
            'sale_type' => $request->sale_type,
            'base_price' => $request->base_price,
            'stock' => $request->stock ?? 0,
            'description' => $request->description,
        ]);

        // ✅ price history only if changed
        if ($oldPrice != $request->base_price) {
            PriceHistory::create([
                'product_id' => $product->id,
                'price' => $request->base_price,
                'date' => now(),
            ]);
        }

        // ✅ Tags
        $product->tags()->sync($request->tags ?? []);

        // ✅ Bulk reset + add
        $product->bulkPrices()->delete();

        if ($request->bulk_qty) {
            foreach ($request->bulk_qty as $i => $qty) {
                if ($qty && $request->bulk_price[$i]) {
                    BulkPrice::create([
                        'product_id' => $product->id,
                        'min_qty' => $qty,
                        'price' => $request->bulk_price[$i],
                    ]);
                }
            }
        }

        // ✅ Thumbnail
        if ($request->hasFile('thumbnail')) {
            $product->clearMediaCollection('product_thumbnail');

            $product->addMediaFromRequest('thumbnail')
                ->toMediaCollection('product_thumbnail');
        }

        // ✅ Gallery (append only)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $product->addMedia($img)
                    ->toMediaCollection('product_gallery');
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted');
    }
}