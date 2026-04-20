@extends('custom.master')
@section('content')

<!--==================================================-->
<!-- Start buddy Breadcumb Area -->
<!--==================================================-->
<div class="breadcumb-area d-flex align-items-center">
	<div class="container">
		<div class="row d-flex align-items-center">
			<div class="col-lg-12">
				<div class="breadcumb-content text-center">
					<div class="breadcumb-title">
						<h4>Checkout</h4>
					</div>
					<ul>
						<li><a href="/"><i class="bi bi-house-door-fill"></i> Home </a></li>
						<li class="rotates"><i class="bi bi-slash-lg"></i>Checkout</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!--==================================================-->
<!-- End buddy Breadcumb Area -->
<!--==================================================-->

<section class="contact_area inner_section pt-80 pb-80">
<div class="container">

@php
    $total = 0;
@endphp

<div class="row">


    <!-- RIGHT: ORDER SUMMARY -->
    <div class="col-lg-6">

    <div class="shadow-sm border rounded p-4 bg-white">

        <h5 class="fw-bold mb-4">Order Summary</h5>

        @php $total = 0; @endphp

        @foreach($cart as $item)

            @php
                $itemTotal = $item['price'] * $item['qty'];
                $total += $itemTotal;

                $unit = match($item['sale_type'] ?? '') {
                    'tray' => 'Tray',
                    'piece' => 'Piece',
                    'weight' => 'Kg',
                    default => 'Unit'
                };
            @endphp

            <!-- PRODUCT ROW -->
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">

                <!-- IMAGE -->
                <div style="width:60px;">
                    <img src="{{ $item['image'] ?: asset('assets/images/default.png') }}"
                         class="img-fluid rounded">
                </div>

                <!-- DETAILS -->
                <div class="flex-grow-1 ms-3">

                    <div class="fw-semibold">
                        {{ $item['name'] }}
                    </div>

                    <small class="text-muted">
                        ₹{{ number_format($item['price'],2) }} / {{ $unit }}
                    </small>

                    <br>

                    <small>
                        Qty: {{ $item['qty'] }} {{ $unit }}
                    </small>

                </div>

                <!-- PRICE -->
                <div class="fw-bold text-end">
                    ₹{{ number_format($itemTotal,2) }}
                </div>

            </div>

        @endforeach

        <!-- SUMMARY -->
        <div class="mt-3">

            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span>₹{{ number_format($total,2) }}</span>
            </div>

            <div class="d-flex justify-content-between mb-2">
                <span>Delivery</span>
                <span class="text-success">Free</span>
            </div>


            <div class="d-flex justify-content-between">
                <strong>Total</strong>
                <strong class="text-primary fs-5">
                    ₹{{ number_format($total,2) }}
                </strong>
            </div>

        </div>

    </div>

</div>

    <!-- LEFT: FORM -->
    <div class="col-lg-6">

       

         <form method="POST" action="{{ route('checkout.store') }}" class="buddy-checkout-form">
@csrf

<h4 class="checkout-title mb-4">Billing Details</h4>

<div class="row">

    <!-- Name -->
    <div class="col-lg-6 mb-3">
        <div class="form-box">
            <input type="text" name="name"
                   value="{{ old('name', $user->name ?? '') }}"
                   placeholder="Full Name *" required>
        </div>
    </div>

    <!-- Phone -->
    <div class="col-lg-6 mb-3">
        <div class="form-box">
            <input type="text" name="phone"
                   value="{{ old('phone', $user->phone ?? '') }}"
                   placeholder="Phone Number *" required>
        </div>
    </div>

    <!-- Address -->
    <div class="col-lg-12 mb-3">
        <div class="form-box message">
            <textarea name="address" rows="3"
                      placeholder="Full Address *" required>{{ old('address', $user->address ?? '') }}</textarea>
        </div>
    </div>

    <!-- Note -->
    <div class="col-lg-12 mb-3">
        <div class="form-box message">
            <textarea name="note" rows="2"
                      placeholder="Order Note (Optional)">{{ old('note') }}</textarea>
        </div>
    </div>

</div>

<!-- BUTTON -->
<div class="checkout-btn-wrap">
    <button type="submit" class="buddy-checkout-btn">
        Place Order <i class="bi bi-arrow-right"></i>
    </button>
</div>

</form>

<style>

.buddy-checkout-form{
    background:#fff;
    padding:35px;
    border-radius:10px;
    box-shadow:0 5px 25px rgba(0,0,0,0.05);
}

/* Title */
.checkout-title{
    font-size:22px;
    font-weight:700;
    color:#1c1c1c;
}

/* Inputs */
.buddy-checkout-form .form-box input,
.buddy-checkout-form .form-box textarea{
    width:100%;
    border:1px solid #e5e5e5;
    padding:12px 15px;
    border-radius:6px;
    font-size:14px;
    transition:0.3s;
}

.buddy-checkout-form .form-box input:focus,
.buddy-checkout-form .form-box textarea:focus{
    border-color:#28a745;
    box-shadow:0 0 0 2px rgba(40,167,69,0.1);
    outline:none;
}

/* Button */
.buddy-checkout-btn{
    width:100%;
    background: linear-gradient(135deg,#28a745,#20c997);
    color:#fff;
    border:none;
    padding:14px;
    font-size:16px;
    font-weight:600;
    border-radius:6px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:8px;
    transition:0.3s;
}

.buddy-checkout-btn:hover{
    background: linear-gradient(135deg,#218838,#17a589);
    transform: translateY(-2px);
    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

</style>

        </div>

    </div>


</div>

</div>
</section>

@endsection