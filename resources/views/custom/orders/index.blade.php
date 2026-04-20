@extends('custom.master')

@section('content')

<div class="container py-120">

    <h2 class="mb-4">My Orders</h2>

    @forelse($orders as $order)
        <div class="card mb-4 shadow-sm border-0">

            {{-- Order Header --}}
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <div>
                    <strong>Order #{{ $order->id }}</strong><br>
                    <small class="text-muted">
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </small>
                </div>

                <div>
                    <span class="badge bg-warning text-dark">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            {{-- Order Body --}}
            <div class="card-body">

                {{-- Products --}}
                @foreach($order->items as $item)
                    <div class="d-flex align-items-center mb-3">

                        {{-- Product Image --}}
                        <div class="me-3">
                            <img 
                                src="{{ $item->product?->getFirstMediaUrl('product_thumbnail') ?: asset('assets/img/shop/01.png') }}"
                                width="70"
                                height="70"
                                style="object-fit: cover; border-radius: 8px;"
                            >
                        </div>

                        {{-- Product Info --}}
                        <div class="flex-grow-1">
                            <strong>{{ $item->product->name ?? 'Product' }}</strong><br>
                            <small class="text-muted">
                                {{ $item->qty }} {{ $item->product->sale_type ?? 'unit' }}
                                × ₹{{ number_format($item->price, 2) }}
                            </small>
                        </div>

                        {{-- Subtotal --}}
                        <div>
                            <strong>
                                ₹{{ number_format($item->qty * $item->price, 2) }}
                            </strong>
                        </div>

                    </div>

                    @if(!$loop->last)
                        <hr>
                    @endif
                @endforeach

                {{-- Summary --}}
                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                    <div>

                        @php
    $grouped = $order->items->groupBy(fn($item) => $item->product->sale_type ?? 'unit');
@endphp

<strong>Total Items:</strong>
@foreach($grouped as $type => $items)
    <span class="theme-btn btn-sm">
        {{ $items->sum('qty') }} {{ ucfirst($type) }}
    </span>
@endforeach
                    </div>
                    <div>
                        <strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="card-footer text-end bg-white">
                <a href="{{ route('orders.show', $order->id) }}" class="theme-btn btn-sm">
                    View Details
                </a>
            </div>

        </div>
    @empty
        <div class="text-center py-5">
            <h4>No Orders Found</h4>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>

</div>

@endsection