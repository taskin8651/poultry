<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class HomeController
{
    public function index()
    {
        $stats = [
            'users'    => User::count(),
            'products' => Product::count(),
            'orders'   => Order::count(),
            'revenue'  => Order::sum('total_amount'),
            'contacts' => Contact::count(),
            'low_stock'=> Product::where('stock', '<=', 5)->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(6)->get();
        $recentContacts = Contact::latest()->take(5)->get();

        return view('home', compact('stats', 'recentOrders', 'recentContacts'));
    }
}
