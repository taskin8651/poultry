@extends('user.layout')

@section('account-title', 'Order Details')
@section('account-subtitle')Invoice for order #{{ $order->id }}@endsection

@section('account-content')

@php
    $statusBadge = [
        'pending'   => 'user-badge-pending',
        'confirmed' => 'user-badge-confirmed',
        'delivered' => 'user-badge-delivered',
        'cancelled' => 'user-badge-cancelled',
    ];
    $siteName = optional(\App\Models\Setting::first())->site_name ?? 'Poultryfarm';
@endphp

<div class="user-card" id="invoice-area">
    <div class="user-card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h3 class="mb-0 fw-bold">Invoice</h3>
                <small style="color:var(--up-muted);">#{{ $order->id }} &middot; {{ $order->created_at?->format('d M Y, h:i A') ?? '-' }}</small>
            </div>

            <div class="text-end">
                <h5 class="mb-1 fw-bold">{{ $siteName }}</h5>
                <span class="user-badge {{ $statusBadge[$order->status] ?? 'user-badge-pending' }}">{{ ucfirst($order->status) }}</span>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 style="color:var(--up-muted);font-size:12px;text-transform:uppercase;letter-spacing:.4px;">Bill To</h6>
                <strong>{{ $order->user->name }}</strong><br>
                <small>{{ $order->user->email }}</small>
                @if($order->user->phone)<br><small>{{ $order->user->phone }}</small>@endif
            </div>

            <div class="col-md-6 text-md-end">
                <h6 style="color:var(--up-muted);font-size:12px;text-transform:uppercase;letter-spacing:.4px;">Order Info</h6>
                <strong>Date:</strong> {{ $order->created_at?->format('d M Y') ?? '-' }}<br>
                <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
                <strong>Payment:</strong> Cash on Delivery<br>
                <strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'pending') }}
            </div>
        </div>

        @if($order->shipping_address1)
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 style="color:var(--up-muted);font-size:12px;text-transform:uppercase;letter-spacing:.4px;">Shipping Address</h6>
                <strong>{{ trim(($order->shipping_first_name ?? '') . ' ' . ($order->shipping_last_name ?? '')) }}</strong><br>
                @if($order->shipping_phone)<small>{{ $order->shipping_phone }}</small><br>@endif
                <small>{{ $order->shipping_address1 }}@if($order->shipping_address2), {{ $order->shipping_address2 }}@endif</small>
            </div>
        </div>
        @endif

        @if($order->tracking_url)
        <div class="row mb-4">
            <div class="col-md-12">
                <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener" class="user-btn">
                    <i class="far fa-truck"></i> Track Order
                </a>
            </div>
        </div>
        @endif

        {{-- Items Table --}}
        <div class="table-responsive">
            <table class="table user-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                    @php
                        $grouped = $order->items->groupBy(fn($item) => $item->product->sale_type ?? 'unit');
                    @endphp

                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img
                                    src="{{ $item->product?->getFirstMediaUrl('product_thumbnail') ?: asset('assets/img/shop/01.png') }}"
                                    width="46"
                                    class="me-2"
                                    style="border-radius:8px;object-fit:cover;"
                                >
                                {{ $item->product->name ?? 'Product' }}
                            </div>
                        </td>
                        <td>{{ $item->qty }} {{ ucfirst($item->product->sale_type ?? 'unit') }}</td>
                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td class="fw-bold">₹{{ number_format($item->qty * $item->price, 2) }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Summary --}}
        <div class="row justify-content-end mt-4">
            <div class="col-md-5">
                <div class="d-flex justify-content-between py-2 border-bottom" style="border-color:var(--up-border) !important;">
                    <span style="color:var(--up-muted);">Total Items</span>
                    <strong>
                        @foreach($grouped as $type => $items)
                            {{ $items->sum('qty') }} {{ ucfirst($type) }}@if(!$loop->last), @endif
                        @endforeach
                    </strong>
                </div>
                @if($order->wallet_used > 0)
                    <div class="d-flex justify-content-between py-2 border-bottom" style="color:var(--up-success);border-color:var(--up-border) !important;">
                        <span>Wallet Used</span>
                        <strong>- ₹{{ number_format($order->wallet_used, 2) }}</strong>
                    </div>
                @endif
                <div class="d-flex justify-content-between py-2">
                    <span style="color:var(--up-muted);">Total Amount</span>
                    <strong style="font-size:16px;">₹{{ number_format($order->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>

        {{-- Note --}}
        @if($order->note)
        <div class="mt-4 p-3" style="background:#FAFAFC;border-radius:var(--up-radius-sm);">
            <strong>Note:</strong>
            <p class="mb-0">{{ $order->note }}</p>
        </div>
        @endif

    </div>
</div>

{{-- Buttons --}}
<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="user-btn">
        <i class="far fa-print"></i> Print Invoice
    </button>

    <a href="{{ route('orders.index') }}" class="user-btn user-btn-outline">
        Back to Orders
    </a>
</div>

@endsection
