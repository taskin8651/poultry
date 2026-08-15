<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
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

        /*
        |--------------------------------------------------------------------------
        | Product Thumbnail
        |--------------------------------------------------------------------------
        */

        $thumbnail = $product->getFirstMedia('product_thumbnail');

        $thumbnailUrl = $thumbnail
            ? $thumbnail->getUrl()
            : null;


        /*
        |--------------------------------------------------------------------------
        | Product Gallery
        |--------------------------------------------------------------------------
        */

        $gallery = $product->getMedia('product_gallery')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'name' => $media->name,
            ];
        })->values();


        /*
        |--------------------------------------------------------------------------
        | Bulk Prices
        |--------------------------------------------------------------------------
        */

        $bulkPrices = $product->bulkPrices->map(function ($bulk) {
            return [
                'id' => $bulk->id,
                'min_qty' => $bulk->min_qty,
                'price' => $bulk->price,
            ];
        })->values();


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

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

                'thumbnail' => $thumbnailUrl,

                'gallery' => $gallery,

                'tags' => $product->tags->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ];
                })->values(),

                'bulk_prices' => $bulkPrices,

                /*
                |--------------------------------------------------------------------------
                | Example Price
                |--------------------------------------------------------------------------
                |
                | Default quantity = 1
                |
                */

                'current_price' => $product->getPrice(1),

                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ],
        ], 200);
    }


    /**
     * Get Product Price By Quantity
     */
    public function price(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|numeric|min:1',
        ]);

        $product = Product::with('bulkPrices')
            ->find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        $qty = $request->qty;

        $price = $product->getPrice($qty);

        return response()->json([
            'success' => true,
            'product_id' => $product->id,
            'quantity' => $qty,
            'price' => $price,
            'total' => $price * $qty,
        ], 200);
    }
}