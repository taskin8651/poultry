@extends('layouts.admin')

@section('content')

@php
    $statusClass = match($order->status) {
        'delivered' => 'badge-success',
        'confirmed' => 'badge-info',
        'cancelled' => 'badge-danger',
        default => 'badge-neutral',
    };
    $paymentStatusClass = $order->payment_status === 'confirmed' ? 'badge-success' : 'badge-neutral';
@endphp

<div class="mx-auto w-full max-w-5xl">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-cart-check-fill"></i>
            </div>

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Order #{{ $order->id }}
                    </h1>

                    <span class="badge-premium {{ $statusClass }}">
                        {{ ucfirst($order->status) }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Placed on {{ $order->created_at?->format('d M Y, h:i A') }}
                </p>

            </div>

        </div>

        <a href="{{ route('admin.orders.index') }}"
           class="btn-premium btn-premium-outline">
            <i class="bi bi-arrow-left"></i>
            Back to Orders
        </a>

    </div>


    {{-- =====================================================
         ORDER INFO
    ====================================================== --}}
    <div class="card-premium mb-6 p-6 sm:p-8">

        <h2 class="mb-5 text-lg font-extrabold text-slate-900">
            Order Information
        </h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Customer
                </p>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $order->user->name ?? '-' }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Total Quantity
                </p>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $order->total_qty }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Total Amount
                </p>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    ₹{{ number_format((float) $order->total_amount, 2) }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Status
                </p>
                <p class="mt-1">
                    <span class="badge-premium {{ $statusClass }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Payment Method
                </p>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    <i class="bi bi-cash-coin text-emerald-600"></i>
                    {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Payment Status
                </p>
                <p class="mt-1">
                    <span class="badge-premium {{ $paymentStatusClass }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </p>
            </div>

            @if($order->shipping_address1)

                <div class="rounded-2xl bg-slate-50 p-4 sm:col-span-2 lg:col-span-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Shipping Address
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-700">
                        {{ trim(($order->shipping_first_name ?? '') . ' ' . ($order->shipping_last_name ?? '')) }}
                        @if($order->shipping_phone) &middot; {{ $order->shipping_phone }} @endif
                        <br>
                        {{ $order->shipping_address1 }}
                        @if($order->shipping_address2), {{ $order->shipping_address2 }}@endif
                    </p>
                </div>

            @endif

            @if($order->tracking_url)

                <div class="rounded-2xl bg-slate-50 p-4 sm:col-span-2 lg:col-span-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Track URL
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-700">
                        <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener" class="text-indigo-600 underline">
                            {{ $order->tracking_url }}
                        </a>
                    </p>
                </div>

            @endif

            @if($order->note)

                <div class="rounded-2xl bg-slate-50 p-4 sm:col-span-2 lg:col-span-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        Note
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $order->note }}
                    </p>
                </div>

            @endif

        </div>

    </div>


    {{-- =====================================================
         UPDATE STATUS
    ====================================================== --}}
    <div class="card-premium mb-6 p-6 sm:p-8">

        <h2 class="mb-5 text-lg font-extrabold text-slate-900">
            Update Status
        </h2>

        <form method="POST"
              action="{{ route('admin.orders.update', $order->id) }}"
              class="flex flex-col gap-4 sm:flex-row sm:items-end sm:flex-wrap">
            @csrf
            @method('PUT')

            <div class="w-full sm:max-w-xs">

                <label for="status" class="label-premium">
                    Order Status
                </label>

                <select id="status" name="status" class="input-premium">
                    <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                    <option value="confirmed" {{ $order->status=='confirmed'?'selected':'' }}>Confirmed</option>
                    <option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>
                </select>

            </div>

            <div class="w-full sm:max-w-xs">

                <label for="payment_status" class="label-premium">
                    Payment Status
                </label>

                <select id="payment_status" name="payment_status" class="input-premium">
                    <option value="pending" {{ ($order->payment_status ?? 'pending')=='pending'?'selected':'' }}>Pending</option>
                    <option value="confirmed" {{ ($order->payment_status ?? 'pending')=='confirmed'?'selected':'' }}>Confirmed</option>
                </select>

            </div>

            <div class="w-full sm:max-w-sm">

                <label for="tracking_url" class="label-premium">
                    Track URL <span class="text-slate-400 font-normal">(add after confirming the order)</span>
                </label>

                <input type="url" id="tracking_url" name="tracking_url" class="input-premium"
                       placeholder="https://..." value="{{ $order->tracking_url }}">

            </div>

            <button type="submit" class="btn-premium btn-premium-primary">
                <i class="bi bi-check2-circle"></i>
                Update Status
            </button>

        </form>

    </div>


    {{-- =====================================================
         ORDER ITEMS
    ====================================================== --}}
    <div class="card-premium overflow-hidden">

        <div class="border-b border-slate-100 p-6 sm:p-8">
            <h2 class="text-lg font-extrabold text-slate-900">
                Products
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Items included in this order
            </p>
        </div>

        <div class="table-premium-wrap">

            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($order->items as $item)
                    <tr>
                        <td class="font-semibold text-slate-800">{{ $item->product->name ?? '-' }}</td>
                        <td>{{ $item->qty }}</td>
                        <td class="font-bold text-slate-800">₹{{ number_format((float) $item->price, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-14 text-center text-sm text-slate-400">
                            No products found for this order.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

    </div>

</div>

@endsection
