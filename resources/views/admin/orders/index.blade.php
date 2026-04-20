@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h1 class="text-2xl font-bold mb-4">Orders</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">#ID</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
            @foreach($orders as $order)
                <tr class="border-t">

                    <td class="p-3">#{{ $order->id }}</td>

                    <td>{{ $order->user->name ?? '-' }}</td>

                    <td>₹{{ $order->total_amount }}</td>

                    <td>
                        <form method="POST"
                              action="{{ route('admin.orders.update', $order->id) }}">
                            @csrf
                            @method('PUT')

                            <select name="status"
                                    onchange="this.form.submit()"
                                    class="border rounded p-1">

                                <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="confirmed" {{ $order->status=='confirmed'?'selected':'' }}>Confirmed</option>
                                <option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>

                            </select>
                        </form>
                    </td>

                    <td class="flex gap-2 p-3">

                        <a href="{{ route('admin.orders.show',$order->id) }}"
                           class="bg-blue-600 text-white px-2 py-1 rounded">
                            View
                        </a>

                        <form method="POST"
                              action="{{ route('admin.orders.destroy',$order->id) }}">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-600 text-white px-2 py-1 rounded">
                                Delete
                            </button>
                        </form>

                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>

</div>

@endsection