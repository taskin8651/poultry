<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // 📦 All Orders (User)
    public function index()
    {
       $orders = Order::with(['items.product.media'])
    ->where('user_id', auth()->id())
    ->latest()
    ->paginate(10);

        return view('custom.orders.index', compact('orders'));
    }

    // 🔍 Single Order Detail
    public function show($id)
    {
        $order = Order::with('items.product', 'user')
            ->where('user_id', auth()->id()) // 🔐 security
            ->findOrFail($id);

        return view('custom.orders.show', compact('order'));
    }
}