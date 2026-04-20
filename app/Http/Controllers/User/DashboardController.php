<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🔥 counts
        $totalOrders = Order::where('user_id', $user->id)->count();

        $pendingOrders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $deliveredOrders = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->count();

        // 🔥 latest orders (last 5)
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'deliveredOrders',
            'recentOrders'
        ));
    }
}