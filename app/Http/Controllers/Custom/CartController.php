<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\OfferService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected OfferService $offers;

    public function __construct(OfferService $offers)
    {
        $this->offers = $offers;
    }

    // -------------------------------------------------------------------------
    // CART PAGE
    // -------------------------------------------------------------------------
    public function index()
    {
        $cart         = session('cart', []);
        $summary      = $this->calcSummary($cart);
        $offerPreview = $this->offers->previewForCart($cart);

        return view('custom.cart', compact('cart', 'summary', 'offerPreview'));
    }

    // -------------------------------------------------------------------------
    // ADD TO CART
    // -------------------------------------------------------------------------
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::with('bulkPrices')->findOrFail($request->product_id);

        if (!$product->status) {
            return response()->json(['success' => false, 'message' => 'Product unavailable'], 422);
        }

        $qty  = (int) $request->quantity;
        $cart = session('cart', []);

        $newQty = $qty + ($cart[$product->id]['quantity'] ?? 0);

        if ($newQty > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Only {$product->stock} {$product->sale_type} available in stock.",
            ], 422);
        }

        $unitPrice = $this->resolvePrice($product, $qty);

        if (isset($cart[$product->id])) {
            $newQty    = $cart[$product->id]['quantity'] + $qty;
            $unitPrice = $this->resolvePrice($product, $newQty);

            $cart[$product->id]['quantity']   = $newQty;
            $cart[$product->id]['unit_price'] = $unitPrice;
            $cart[$product->id]['subtotal']   = round($unitPrice * $newQty, 2);
        } else {
            $cart[$product->id] = [
                'product_id'  => $product->id,
                'name'        => $product->name,
                'image'       => $product->getFirstMediaUrl('product_thumbnail'),
                'sale_type'   => $product->sale_type,
                'base_price'  => (float) $product->base_price,
                'bulk_prices' => $product->bulkPrices
                    ->map(fn($b) => [
                        'min_qty' => (int)$b->min_qty,
                        'price'   => (float)$b->price
                    ])->toArray(),
                'quantity'    => $qty,
                'unit_price'  => $unitPrice,
                'subtotal'    => round($unitPrice * $qty, 2),
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => 'Added to cart',
            'cart_count' => count($cart),
        ]);
    }

    // -------------------------------------------------------------------------
    // UPDATE CART
    // -------------------------------------------------------------------------
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);

        if (!isset($cart[$request->product_id])) {
            return response()->json(['success' => false], 404);
        }

        $product = Product::with('bulkPrices')->find($request->product_id);
        $qty     = (int) $request->quantity;

        if ($qty > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => "Only {$product->stock} {$product->sale_type} available in stock.",
            ], 422);
        }

        $unitPrice = $this->resolvePrice($product, $qty);

        $cart[$request->product_id]['quantity']   = $qty;
        $cart[$request->product_id]['unit_price'] = $unitPrice;
        $cart[$request->product_id]['subtotal']   = round($unitPrice * $qty, 2);

        session(['cart' => $cart]);

        $summary = $this->calcSummary($cart);

        return response()->json([
            'success' => true,
            'summary' => $summary,
        ]);
    }

    // -------------------------------------------------------------------------
    // REMOVE ITEM
    // -------------------------------------------------------------------------
    public function remove(Request $request)
{
    $request->validate([
        'product_id' => 'required|integer'
    ]);

    $cart = session('cart', []);

    unset($cart[$request->product_id]);

    session(['cart' => $cart]);

    $summary = $this->calcSummary($cart);

    return response()->json([
        'success'    => true,
        'cart_count' => count($cart),
        'summary'    => $summary,
    ]);
}

    // -------------------------------------------------------------------------
    // PRICE LOGIC (BULK)
    // -------------------------------------------------------------------------
    private function resolvePrice(Product $product, int $qty): float
    {
        $price = $product->base_price;

        foreach ($product->bulkPrices as $tier) {
            if ($qty >= $tier->min_qty) {
                $price = $tier->price;
            }
        }

        return (float) $price;
    }

    // -------------------------------------------------------------------------
    // FINAL SUMMARY
    // -------------------------------------------------------------------------
    private function calcSummary(array $cart): array
    {
        $subTotal = round(collect($cart)->sum('subtotal'), 2);

        return [
            'sub_total' => $subTotal,
            'total_qty' => collect($cart)->sum('quantity'),
            'total'     => $subTotal,
        ];
    }
}