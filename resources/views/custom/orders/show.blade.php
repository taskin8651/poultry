@extends('custom.master')

@section('content')

<div class="container py-120">

    <div class="card shadow-sm p-4" id="invoice-area">

        {{-- Header --}}
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h3 class="mb-0">Invoice</h3>
                <small>#{{ $order->id }}</small>
            </div>

            <div class="text-end">
                <h5>Your Company</h5>
                <small>Mithapur, Bihar</small>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Bill To:</h6>
                <strong>{{ $order->user->name }}</strong><br>
                <small>{{ $order->user->email }}</small>
            </div>

            <div class="col-md-6 text-end">
                <h6>Order Info:</h6>
                <strong>Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
                <strong>Status:</strong> {{ ucfirst($order->status) }}
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
                    

                    @foreach($order->items as $item)
                      @php
    $grouped = $order->items->groupBy(fn($item) => $item->product->sale_type ?? 'unit');
@endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img 
                                    src="{{ $item->product?->getFirstMediaUrl('product_thumbnail') ?: asset('assets/img/shop/01.png') }}"
                                    width="50"
                                    class="me-2"
                                >
                                {{ $item->product->name ?? 'Product' }}
                            </div>
                        </td>
<td>
    {{ $item->qty }} {{ ucfirst($item->product->sale_type ?? 'unit') }}
</td>                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td>₹{{ number_format($item->qty * $item->price, 2) }}</td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        {{-- Summary --}}
        <div class="row justify-content-end mt-4">
            <div class="col-md-4">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total Items</span>
                    

<strong>
    @foreach($grouped as $type => $items)
        {{ $items->sum('qty') }} {{ ucfirst($type) }}@if(!$loop->last), @endif
    @endforeach
</strong>
                    </li>
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
            <p>{{ $order->note }}</p>
        </div>
        @endif

    </div>

    {{-- Buttons --}}
    <div class="text-center mt-4">
        <button onclick="printInvoice()" class="theme-btn">
            <i class="far fa-print"></i> Print Invoice
        </button>

        <a href="{{ route('orders.index') }}" class="theme-btn" style="background:#6c757d;">
            Back to Orders
        </a>
    </div>

</div>

@endsection