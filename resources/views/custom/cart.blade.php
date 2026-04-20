@extends('custom.master')
@section('content')

<div class="breadcumb-area d-flex align-items-center">
    <div class="container text-center">
        <h4>Cart</h4>
        <a href="/">Home</a> / Cart
    </div>
</div>

<div class="cart-section pt-80 pb-80">
<div class="container">

@php
    $cart = session('cart', []);
    $total = 0;
@endphp

@if(count($cart))

<div class="table-responsive">
<table class="table table-bordered text-center align-middle">

<thead class="table-dark">
<tr>
    <th>Image</th>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

@foreach($cart as $id => $item)

@php
    $itemTotal = $item['price'] * $item['qty'];
    $total += $itemTotal;
@endphp

<tr>

<td>
    <img src="{{ $item['image'] ?: asset('assets/images/default.png') }}"
         width="70">
</td>

<td>{{ $item['name'] }}</td>

<td>
    ₹{{ number_format($item['price'], 2) }}
    <br>
    <small class="text-muted">
        per {{ $item['sale_type'] == 'tray' ? 'Tray' : ($item['sale_type'] == 'piece' ? 'Piece' : 'Kg') }}
    </small>
</td>

<td>{{ $item['qty'] }}
     <br>
    <small class="text-muted">
        per {{ $item['sale_type'] == 'tray' ? 'Tray' : ($item['sale_type'] == 'piece' ? 'Piece' : 'Kg') }}
    </small>
</td>

<td>₹{{ number_format($itemTotal, 2) }} 
    
</td>
<td>
    <button class="remove-btn"
        style="background:#dc3545;color:white;border:none;padding:6px 10px;border-radius:4px;"
        data-id="{{ $id }}">
        Remove
    </button>
</td>

</tr>

@endforeach

</tbody>
</table>
</div>
<div class="row mt-5 justify-content-end">

    <div class="col-lg-5">

        <div class="shadow-sm rounded p-4 bg-white border">

            <h5 class="fw-bold mb-3">Order Summary</h5>

            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span>₹{{ number_format($total, 2) }}</span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Delivery</span>
                <span class="text-success">Free</span>
            </div>


            <div class="d-flex justify-content-between mb-3">
                <strong>Total</strong>
                <strong class="text-primary fs-5">
                    ₹{{ number_format($total, 2) }}
                </strong>
            </div>

          @if(count(session('cart', [])) > 0)

    @auth
        <a href="{{ route('checkout.index') }}"
           class="btn btn-success w-100 py-3 fw-semibold d-flex justify-content-center align-items-center gap-2">
            Proceed to Checkout
            <i class="bi bi-arrow-right"></i>
        </a>
    @else 
        <a href="{{ route('login') }}"
           class="btn btn-warning w-100 py-3 fw-semibold">
            Login to Checkout
        </a>
    @endauth

@else
    <button class="btn btn-secondary w-100 py-3" disabled>
        Cart is Empty   
    </button>
@endif  

            <a href="{{ route('shop') }}"
               class="btn btn-outline-secondary w-100 mt-2">
                Continue Shopping
            </a>

            

        </div>

    </div>

</div>

@else

<div class="text-center py-5">
    <h4>Your cart is empty 😢</h4>
    <p>Add products to continue</p>

    <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
        Go to Shop
    </a>
</div>

@endif

</div>
</div>

{{-- REMOVE SCRIPT --}}
<script>
document.querySelectorAll('.remove-btn').forEach(btn => {

    btn.addEventListener('click', function(){

        let id = this.dataset.id;

        fetch("{{ route('cart.remove') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: id
            })
        })
        .then(res => res.json())
        .then(() => location.reload());

    });

});
</script>

@endsection