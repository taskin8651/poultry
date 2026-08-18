<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;



class OrderController extends Controller
{
    protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

public function index()
{
    $orders = Order::with('user')->latest()->paginate(10);
    return view('admin.orders.index', compact('orders'));
}

public function show($id)
{
    $order = Order::with('items.product','user')->findOrFail($id);
    return view('admin.orders.show', compact('order'));
}

public function update(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $order->update([
        'status' => $request->status
    ]);

    $this->notifier->toUser(
        $order->user,
        'Order status updated',
        "Your order #{$order->id} is now {$order->status}.",
        'info',
        route('orders.show', $order->id)
    );

    return back()->with('success','Order status updated');
}

public function destroy($id)
{
    Order::findOrFail($id)->delete();
    return back()->with('success','Order deleted');
}
}
