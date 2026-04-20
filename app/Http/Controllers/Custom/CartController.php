<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
{
    $product = Product::with('bulkPrices')->findOrFail($request->product_id);

    $qty = $request->qty ?? 1;

    // ✅ BULK PRICE APPLY
    $price = $product->getPrice($qty);

    $cart = session()->get('cart', []);

    if(isset($cart[$product->id])) {

        $cart[$product->id]['qty'] += $qty;

        // 🔥 update price again
        $newQty = $cart[$product->id]['qty'];
        $cart[$product->id]['price'] = $product->getPrice($newQty);

    } else {

        $cart[$product->id] = [
            "name" => $product->name,
            "price" => $price,
            "image" => $product->getFirstMediaUrl('product_thumbnail'),
            "qty" => $qty,
            "sale_type" => $product->sale_type,
        ];
    }

    session()->put('cart', $cart);

    return response()->json(['success' => true]);
}

    public function index()
    {
        $cart = session()->get('cart', []);
        return view('custom.cart', compact('cart'));
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Removed');
    }
}