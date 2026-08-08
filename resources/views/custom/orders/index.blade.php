@extends('custom.account.layout')

@section('account-title', 'My Orders')

@section('account-content')

@php
    $statusBadge = [
        'pending'   => 'bg-secondary',
        'confirmed' => 'bg-info text-dark',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-danger',
    ];
@endphp

@forelse($orders as $order)
    <div class="card mb-4 shadow-sm border-0" style="border-radius:14px;overflow:hidden;">

        {{-- Order Header --}}
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <div>
                <strong>Order #{{ $order->id }}</strong><br>
                <small class="text-muted">
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </small>
            </div>

            <div>
                <span class="badge {{ $statusBadge[$order->status] ?? 'bg-secondary' }}">
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
                    @if($order->wallet_used > 0)
                        <div class="small text-success">Wallet used: ₹{{ number_format($order->wallet_used, 2) }}</div>
                    @endif
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
        <i class="far fa-box-open fa-3x text-muted mb-3 d-block"></i>
        <h4>No Orders Found</h4>
        <p class="text-muted">You haven't placed any orders yet.</p>
        <a href="{{ route('shop') }}" class="theme-btn mt-2">Start Shopping</a>
    </div>
@endforelse

{{-- Pagination --}}
<div class="mt-4">
    {{ $orders->links() }}
</div>

@endsection
