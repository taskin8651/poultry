@extends('custom.account.layout')

@section('account-title', 'Order Details')

@section('account-content')

@php
    $statusBadge = [
        'pending'   => 'bg-secondary',
        'confirmed' => 'bg-info text-dark',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-danger',
    ];
    $siteName = optional(\App\Models\Setting::first())->site_name ?? 'Poultryfarm';
@endphp

<div class="card shadow-sm border-0 p-4" id="invoice-area" style="border-radius:14px;">

    {{-- Header --}}
    <div class="d-flex justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-0">Invoice</h3>
            <small class="text-muted">#{{ $order->id }} &middot; {{ $order->created_at->format('d M Y, h:i A') }}</small>
        </div>

        <div class="text-end">
            <h5 class="mb-0">{{ $siteName }}</h5>
            <span class="badge {{ $statusBadge[$order->status] ?? 'bg-secondary' }} mt-1">{{ ucfirst($order->status) }}</span>
        </div>
    </div>

    {{-- Customer Info --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-muted">Bill To</h6>
            <strong>{{ $order->user->name }}</strong><br>
            <small>{{ $order->user->email }}</small>
            @if($order->user->phone)<br><small>{{ $order->user->phone }}</small>@endif
        </div>

        <div class="col-md-6 text-md-end">
            <h6 class="text-muted">Order Info</h6>
            <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
            <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
            <strong>Payment:</strong> Cash on Delivery
        </div>
    </div>

    {{-- Items Table --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="bg-light">
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
                                width="50"
                                class="me-2"
                                style="border-radius:6px;object-fit:cover;"
                            >
                            {{ $item->product->name ?? 'Product' }}
                        </div>
                    </td>
                    <td>{{ $item->qty }} {{ ucfirst($item->product->sale_type ?? 'unit') }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>₹{{ number_format($item->qty * $item->price, 2) }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- Summary --}}
    <div class="row justify-content-end mt-4">
        <div class="col-md-5">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Items</span>
                    <strong>
                        @foreach($grouped as $type => $items)
                            {{ $items->sum('qty') }} {{ ucfirst($type) }}@if(!$loop->last), @endif
                        @endforeach
                    </strong>
                </li>
                @if($order->wallet_used > 0)
                    <li class="list-group-item d-flex justify-content-between text-success">
                        <span>Wallet Used</span>
                        <strong>- ₹{{ number_format($order->wallet_used, 2) }}</strong>
                    </li>
                @endif
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total Amount</span>
                    <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                </li>
            </ul>
        </div>
    </div>

    {{-- Note --}}
    @if($order->note)
    <div class="mt-4">
        <strong>Note:</strong>
        <p class="mb-0">{{ $order->note }}</p>
    </div>
    @endif

</div>

{{-- Buttons --}}
<div class="text-center mt-4 no-print">
    <button onclick="window.print()" class="theme-btn">
        <i class="far fa-print"></i> Print Invoice
    </button>

    <a href="{{ route('orders.index') }}" class="theme-btn" style="background:#6c757d;">
        Back to Orders
    </a>
</div>

<style>
    @media print {
        .no-print, header, footer, .col-lg-3, .site-breadcrumb { display: none !important; }
        .col-lg-9 { width: 100% !important; flex: 0 0 100% !important; max-width: 100% !important; }
    }
</style>

@endsection
