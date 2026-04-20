<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
{
    $cart = session('cart', []);

    if(empty($cart)){
        return redirect()->route('cart.index');
    }

    $user = auth()->user();

    return view('custom.checkout', compact('cart', 'user'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'address' => 'required|string',
            'note'    => 'nullable|string'
        ]);

        $cart = session('cart', []);
        if (!$cart || count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        DB::beginTransaction();

        try {

            $totalQty = 0;
            $totalAmount = 0;

            foreach ($cart as $item) {
                $totalQty += $item['qty'];
                $totalAmount += ($item['price'] * $item['qty']);
            }

            // 🔥 Create Order
            $order = Order::create([
                'user_id' => auth()->id() ?? null,
                'total_qty' => $totalQty,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'note' => $request->note,
            ]);

            // 🔥 Save Items
            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            // 🔥 Clear cart
            session()->forget('cart');

            return redirect()->route('cart.index')->with('success', 'Order placed successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong');
        }
    }
}
