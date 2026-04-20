@extends('custom.master')

@section('content')

{{-- Breadcrumb --}}
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/breadcrumb.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Order Confirmed</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Order Confirmed</li>
        </ul>
    </div>
</div>

{{-- Success Section --}}
<div class="shop-checkout py-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">

                {{-- Icon --}}
                <div class="mb-4">
                    <span style="font-size: 72px; color: #28a745;">
                        <i class="far fa-check-circle"></i>
                    </span>
                </div>

                <h2 class="mb-2">Thank You for Your Order!</h2>
                <p class="text-muted mb-4">
                    Your order has been placed successfully. We will process it shortly.
                </p>

                {{-- Order Info Card --}}
                <div class="checkout cart-summary text-start mb-4">
                    <h4 class="mb-30">Order Details</h4>
                    <ul>
                        <li>
                            <strong>Order ID:</strong>
                            <span>#{{ $order->id }}</span>
                        </li>
                        <li>
                            <strong>Customer:</strong>
                            <span>{{ $order->user->name }}</span>
                        </li>
                        <li>
                            <strong>Total Items:</strong>
                            <span>{{ $order->total_qty }}</span>
                        </li>
                        <li>
                            <strong>Status:</strong>
                            <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                        </li>
                        @if($order->note)
                        <li>
                            <strong>Note:</strong>
                            <span>{{ $order->note }}</span>
                        </li>
                        @endif
                        <li class="cart-total">
                            <strong>Total Amount:</strong>
                            <span>₹ {{ number_format($order->total_amount, 2) }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Ordered Items --}}
                <div class="checkout cart-summary text-start mb-4">
                    <h4 class="mb-30">Items Ordered</h4>
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong>{{ $item->product->name ?? 'Product' }}</strong><br>
                            <small class="text-muted">
                                {{ $item->qty }} {{ $item->product->sale_type ?? 'unit' }}
                                × ₹ {{ number_format($item->price, 2) }}
                            </small>
                        </div>
                        <span>₹ {{ number_format($item->qty * $item->price, 2) }}</span>
                    </div>
                    @if(!$loop->last)<hr class="my-2">@endif
                    @endforeach
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="{{ url('/') }}" class="theme-btn">
                        <i class="far fa-home me-1"></i> Back to Home
                    </a>
                    <a href="{{ route('shop') }}" class="theme-btn" style="background: #6c757d;">
                        <i class="far fa-shopping-bag me-1"></i> Continue Shopping
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection