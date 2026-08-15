<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Get All Products
     */
    public function index(Request $request)
    {
        $products = Product::with([
            'category:id,name',
            'tags:id,name',
            'bulkPrices:id,product_id,min_qty,price',
        ])
        ->where('status', 1)
        ->latest()
        ->get();

        $data = $products->map(function ($product) {

            // Thumbnail
            $thumbnail = $product->getFirstMedia('product_thumbnail');

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,

                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ] : null,

                'type' => $product->type,
                'sale_type' => $product->sale_type,

                'base_price' => $product->base_price,
                'current_price' => $product->getPrice(1),

                'stock' => $product->stock,

                'description' => $product->description,

                'status' => $product->status,

                'thumbnail' => $thumbnail
                    ? $thumbnail->getUrl()
                    : null,

                'tags' => $product->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ];
                })->values(),

                'bulk_prices' => $product->bulkPrices->map(function ($bulk) {
                    return [
                        'id' => $bulk->id,
                        'min_qty' => $bulk->min_qty,
                        'price' => $bulk->price,
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Products fetched successfully.',
            'total' => $data->count(),
            'products' => $data,
        ], 200);
    }


    /**
     * Get Product Details By ID
     */
    public function show($id)
    {
        $product = Product::with([
            'category:id,name',
            'tags:id,name',
            'bulkPrices:id,product_id,min_qty,price',
        ])->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $thumbnail = $product->getFirstMedia('product_thumbnail');

        $gallery = $product->getMedia('product_gallery')
            ->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'name' => $media->name,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Product details fetched successfully.',

            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,

                'category' => $product->category ? [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                ] : null,

                'type' => $product->type,
                'sale_type' => $product->sale_type,

                'base_price' => $product->base_price,
                'stock' => $product->stock,

                'description' => $product->description,
                'status' => $product->status,

                'thumbnail' => $thumbnail
                    ? $thumbnail->getUrl()
                    : null,

                'gallery' => $gallery,

                'tags' => $product->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ];
                })->values(),

                'bulk_prices' => $product->bulkPrices
                    ->map(function ($bulk) {
                        return [
                            'id' => $bulk->id,
                            'min_qty' => $bulk->min_qty,
                            'price' => $bulk->price,
                        ];
                    })
                    ->values(),

                'current_price' => $product->getPrice(1),

                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ],
        ], 200);
    }
}