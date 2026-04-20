<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // must be logged in
    }

    // -------------------------------------------------------------------------
    // GET /checkout
    // -------------------------------------------------------------------------
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $user    = Auth::user();
        $summary = $this->calcSummary($cart);

        return view('custom.checkout', compact('cart', 'user', 'summary'));
    }

    // -------------------------------------------------------------------------
    // POST /checkout
    // -------------------------------------------------------------------------
    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $user    = Auth::user();
        $summary = $this->calcSummary($cart);

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'user_id'      => $user->id,
                'total_qty'    => collect($cart)->sum('quantity'),
                'total_amount' => $summary['total'],
                'status'       => 'pending',
                'note'         => $request->note,
            ]);

            // Create order items
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'qty'        => $item['quantity'],
                    'price'      => $item['unit_price'],  // bulk-resolved price
                ]);
            }

            DB::commit();

            // Clear cart after successful order
            session()->forget('cart');

            return redirect()->route('checkout.success', $order->id)
                             ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    // -------------------------------------------------------------------------
    // HELPER
    // -------------------------------------------------------------------------
    private function calcSummary(array $cart): array
    {
        $subTotal = round(collect($cart)->sum('subtotal'), 2);

        return [
            'sub_total'  => $subTotal,
            'total_qty'  => collect($cart)->sum('quantity'),
            'total'      => $subTotal,
        ];
    }


    public function success(Order $order)
{
    abort_if($order->user_id !== Auth::id(), 403);
    return view('custom.checkout-success', compact('order'));
}
}
