@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">
        Order #{{ $order->id }}
    </h1>

    {{-- USER --}}
    <div class="bg-white p-4 rounded shadow mb-4">
        <p><strong>User:</strong> {{ $order->user->name }}</p>
        <p><strong>Total:</strong> ₹{{ $order->total_amount }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    </div>

    {{-- ITEMS --}}
    <div class="bg-white p-4 rounded shadow">

        <h2 class="font-semibold mb-3">Products</h2>

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>

            <tbody>
            @foreach($order->items as $item)
                <tr class="border-t">
                    <td class="p-2">{{ $item->product->name ?? '-' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>₹{{ $item->price }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</div>

@endsection