<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CART SESSION STRUCTURE
    |--------------------------------------------------------------------------
    | session('cart') = [
    |   product_id => [
    |       'product_id'  => int,
    |       'name'        => string,
    |       'image'       => string (url),
    |       'sale_type'   => string,
    |       'base_price'  => float,
    |       'bulk_prices' => [ ['min_qty'=>int, 'price'=>float], ... ],
    |       'quantity'    => int,
    |       'unit_price'  => float,   // applied price after bulk check
    |       'subtotal'    => float,
    |   ],
    |   ...
    | ]
    */

    // -------------------------------------------------------------------------
    // GET /cart
    // -------------------------------------------------------------------------
    public function index()
    {
        $cart     = session('cart', []);
        $summary  = $this->calcSummary($cart);

        return view('custom.cart', compact('cart', 'summary'));
    }

    // -------------------------------------------------------------------------
    // POST /add-to-cart
    // -------------------------------------------------------------------------
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::with('bulkPrices')->findOrFail($request->product_id);

        if (!$product->status) {
            return response()->json(['success' => false, 'message' => 'Product is unavailable.'], 422);
        }

        $qty       = (int) $request->quantity;
        $unitPrice = $this->resolvePrice($product, $qty);
        $subtotal  = round($unitPrice * $qty, 2);

        $cart = session('cart', []);

        if (isset($cart[$product->id])) {
            // Already in cart — update qty & recalculate
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
                                    ->map(fn($b) => ['min_qty' => (int)$b->min_qty, 'price' => (float)$b->price])
                                    ->toArray(),
                'quantity'    => $qty,
                'unit_price'  => $unitPrice,
                'subtotal'    => $subtotal,
            ];
        }

        session(['cart' => $cart]);

        return response()->json([
            'success'    => true,
            'message'    => $product->name . ' added to cart!',
            'cart_count' => count($cart),
        ]);
    }

    // -------------------------------------------------------------------------
    // POST /remove-cart
    // -------------------------------------------------------------------------
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|integer']);

        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        $summary = $this->calcSummary($cart);

        return response()->json([
            'success'    => true,
            'message'    => 'Item removed.',
            'cart_count' => count($cart),
            'summary'    => $summary,
        ]);
    }

    // -------------------------------------------------------------------------
    // POST /update-cart  (add this route if needed)
    // -------------------------------------------------------------------------
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);

        if (!isset($cart[$request->product_id])) {
            return response()->json(['success' => false, 'message' => 'Item not in cart.'], 404);
        }

        $item    = &$cart[$request->product_id];
        $qty     = (int) $request->quantity;

        // Rebuild bulk_prices into collection-like array for resolvePrice
        $product    = Product::with('bulkPrices')->find($request->product_id);
        $unitPrice  = $this->resolvePrice($product, $qty);

        $item['quantity']   = $qty;
        $item['unit_price'] = $unitPrice;
        $item['subtotal']   = round($unitPrice * $qty, 2);

        session(['cart' => $cart]);

        $summary = $this->calcSummary($cart);

        return response()->json([
            'success'   => true,
            'subtotal'  => number_format($item['subtotal'], 2),
            'summary'   => $summary,
        ]);
    }

    // -------------------------------------------------------------------------
    // HELPERS
    // -------------------------------------------------------------------------

    /**
     * Resolve unit price for a given qty using bulk pricing tiers.
     */
    private function resolvePrice(Product $product, int $qty): float
    {
        $price = (float) $product->base_price;

        foreach ($product->bulkPrices as $tier) {
            if ($qty >= $tier->min_qty) {
                $price = (float) $tier->price;
            }
        }

        return $price;
    }

    /**
     * Calculate cart summary (subtotal, tax, discount, total).
     * Adjust VAT rate / discount logic as needed.
     */
    private function calcSummary(array $cart): array
    {
        $subTotal = collect($cart)->sum('subtotal');
        $vat      = round($subTotal * 0.05, 2);   // 5% VAT — change as needed
        $discount = 0.00;                           // hook in coupon logic here
        $total    = round($subTotal + $vat - $discount, 2);

        return [
            'sub_total' => $subTotal,
            'vat'       => $vat,
            'discount'  => $discount,
            'total'     => $total,
        ];
    }
}