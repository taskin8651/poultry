@extends('user.layout')

@section('account-title', 'My Orders')
@section('account-subtitle', 'Track and manage everything you have ordered.')

@section('account-content')

@php
    $statusBadge = [
        'pending'   => 'user-badge-pending',
        'confirmed' => 'user-badge-confirmed',
        'delivered' => 'user-badge-delivered',
        'cancelled' => 'user-badge-cancelled',
    ];
@endphp

@forelse($orders as $order)
    <div class="user-card mb-4">

        {{-- Order Header --}}
        <div class="user-card-header" style="background:#FAFAFC;">
            <div>
                <strong>Order #{{ $order->id }}</strong>
                <div class="small" style="color:var(--up-muted);font-weight:500;">
                    {{ $order->created_at?->format('d M Y, h:i A') ?? '-' }}
                </div>
            </div>
            <span class="user-badge {{ $statusBadge[$order->status] ?? 'user-badge-pending' }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        {{-- Order Body --}}
        <div class="user-card-body">

            {{-- Products --}}
            @foreach($order->items as $item)
                <div class="d-flex align-items-center mb-3">

                    {{-- Product Image --}}
                    <div class="me-3">
                        <img
                            src="{{ $item->product?->getFirstMediaUrl('product_thumbnail') ?: asset('assets/img/shop/01.png') }}"
                            width="64"
                            height="64"
                            style="object-fit: cover; border-radius: 12px;"
                        >
                    </div>

                    {{-- Product Info --}}
                    <div class="flex-grow-1">
                        <strong>{{ $item->product->name ?? 'Product' }}</strong><br>
                        <small style="color:var(--up-muted);">
                            {{ $item->qty }} {{ $item->product->sale_type ?? 'unit' }}
                            × ₹{{ number_format($item->price, 2) }}
                        </small>
                    </div>

                    {{-- Subtotal --}}
                    <div class="fw-bold">
                        ₹{{ number_format($item->qty * $item->price, 2) }}
                    </div>

                </div>

                @if(!$loop->last)
                    <hr style="border-color:var(--up-border);">
                @endif
            @endforeach

            {{-- Summary --}}
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top" style="border-color:var(--up-border) !important;">
                <div>
                    @php
                        $grouped = $order->items->groupBy(fn($item) => $item->product->sale_type ?? 'unit');
                    @endphp

                    <strong class="me-2">Total Items:</strong>
                    @foreach($grouped as $type => $items)
                        <span class="user-badge user-badge-confirmed">
                            {{ $items->sum('qty') }} {{ ucfirst($type) }}
                        </span>
                    @endforeach
                </div>
                <div class="text-end">
                    <strong>Total:</strong> ₹{{ number_format($order->total_amount, 2) }}
                    @if($order->wallet_used > 0)
                        <div class="small" style="color:var(--up-success);">Wallet used: ₹{{ number_format($order->wallet_used, 2) }}</div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="text-end p-3 px-4" style="background:#FAFAFC;border-top:1px solid var(--up-border);">
            <a href="{{ route('orders.show', $order->id) }}" class="user-btn user-btn-sm">
                View Details
            </a>
        </div>

    </div>
@empty
    <div class="user-card">
        <div class="user-empty">
            <i class="far fa-box-open"></i>
            <h5 class="mb-1" style="color:var(--up-text);">No Orders Found</h5>
            <p class="mb-3">You haven't placed any orders yet.</p>
            <a href="{{ route('shop') }}" class="user-btn">Start Shopping</a>
        </div>
    </div>
@endforelse

{{-- Pagination --}}
<div class="mt-4">
    {{ $orders->links() }}
</div>

@endsection
