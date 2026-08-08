@extends('layouts.admin')

@section('content')

<div class="mx-auto w-full">

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
                        Orders
                    </h1>

                    <span class="badge-premium badge-info">
                        {{ $orders->total() }} Orders
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    View and manage customer orders
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}
    @if(session('success'))

        <div class="alert-premium-success mb-6">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>

    @endif


    {{-- =====================================================
         ORDERS TABLE
    ====================================================== --}}
    <div class="card-premium overflow-hidden">

        <div class="table-premium-wrap">

            <table class="table-premium">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($orders as $order)

                    @php
                        $statusClass = match($order->status) {
                            'delivered' => 'badge-success',
                            'confirmed' => 'badge-info',
                            'cancelled' => 'badge-danger',
                            default => 'badge-neutral',
                        };
                    @endphp

                    <tr>

                        <td class="font-bold text-slate-800">#{{ $order->id }}</td>

                        <td>{{ $order->user->name ?? '-' }}</td>

                        <td class="font-bold text-slate-800">
                            ₹{{ number_format((float) $order->total_amount, 2) }}
                        </td>

                        <td>

                            <div class="flex items-center gap-3">

                                <span class="badge-premium {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>

                                <form method="POST"
                                      action="{{ route('admin.orders.update', $order->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <select name="status"
                                            onchange="this.form.submit()"
                                            class="input-premium !w-auto !py-2 text-xs">

                                        <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status=='confirmed'?'selected':'' }}>Confirmed</option>
                                        <option value="delivered" {{ $order->status=='delivered'?'selected':'' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>

                                    </select>
                                </form>

                            </div>

                        </td>

                        <td>

                            <div class="flex items-center gap-2">

                                <a href="{{ route('admin.orders.show',$order->id) }}"
                                   class="btn-premium btn-premium-primary !px-3 !py-2 text-xs">
                                    <i class="bi bi-eye-fill"></i>
                                    View
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.orders.destroy',$order->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this order?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn-premium btn-premium-danger !px-3 !py-2 text-xs">
                                        <i class="bi bi-trash3"></i>
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="py-14 text-center text-sm text-slate-400">
                            No orders found.
                        </td>
                    </tr>

                @endforelse
                </tbody>
            </table>

        </div>

    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>

</div>

@endsection
