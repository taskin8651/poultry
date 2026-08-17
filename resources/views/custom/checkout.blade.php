@extends('custom.master')

@section('content')

{{-- Breadcrumb --}}
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/breadcrumb.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Shop Checkout</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Shop Checkout</li>
        </ul>
    </div>
</div>

{{-- Checkout --}}
<div class="shop-checkout py-120">
    <div class="container">
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf
            <div class="row">

                {{-- LEFT: Billing + Shipping --}}
                <div class="col-lg-8">

                    {{-- Billing Address (auto-filled from user) --}}
                    <div class="checkout-widget">
                        <h4 class="checkout-widget-title">Billing Address</h4>
                        <div class="checkout-form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control"
                                               value="{{ $user->first_name ?? explode(' ', $user->name)[0] }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control"
                                               value="{{ $user->last_name ?? (explode(' ', $user->name)[1] ?? '') }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control"
                                               value="{{ $user->email }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" class="form-control"
                                               value="{{ $user->phone ?? '' }}"
                                               readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control"
                                               value="{{ $user->address ?? '' }}"
                                               readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shipping Address --}}
                    <div class="checkout-widget">
                        <h4 class="checkout-widget-title">Shipping Address</h4>

                        {{-- Same as billing toggle --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="same-as-billing" checked>
                            <label class="form-check-label" for="same-as-billing">
                                Same as billing address
                            </label>
                        </div>

                        <div class="checkout-form" id="shipping-fields">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" name="shipping_first_name" class="form-control"
                                               placeholder="First Name"
                                               value="{{ $user->first_name ?? explode(' ', $user->name)[0] }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="shipping_last_name" class="form-control"
                                               placeholder="Last Name"
                                               value="{{ $user->last_name ?? (explode(' ', $user->name)[1] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="shipping_phone" class="form-control"
                                               placeholder="Phone"
                                               value="{{ $user->phone ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Address Line 1</label>
                                        <input type="text" name="shipping_address1" class="form-control"
                                               placeholder="Address Line 1"
                                               value="{{ $user->address ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Address Line 2 <span class="text-muted">(optional)</span></label>
                                        <input type="text" name="shipping_address2" class="form-control"
                                               placeholder="Address Line 2">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Additional Info <span class="text-muted">(optional)</span></label>
                                        <textarea class="form-control" name="note" cols="30" rows="4"
                                                  placeholder="Any delivery instructions or notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: Order Summary --}}
                <div class="col-lg-4">
                    <div class="checkout cart-summary">
                        <h4 class="mb-30">Order Summary</h4>

                        {{-- Cart items list --}}
                        <div class="checkout-items mb-3">
                            @foreach($cart as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong>{{ $item['name'] }}</strong><br>
                                    <small class="text-muted">
                                        {{ $item['quantity'] }} × ₹{{ number_format($item['unit_price'], 2) }}
                                        @if($item['unit_price'] < $item['base_price'])
                                            <span class="badge bg-success ms-1" style="font-size:10px;">Bulk</span>
                                        @endif
                                    </small>
                                </div>
                                <span>₹ {{ number_format($item['subtotal'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>

                        @if(count($offerPreview))
                            <div class="mb-3 p-3" style="background:#fff7ed;border:1px solid #fde3c4;border-radius:8px;">
                                <p class="mb-2 fw-bold"><i class="far fa-gift text-warning"></i> You'll earn cashback on this order:</p>
                                @foreach($offerPreview as $entry)
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>{{ $entry['offer']->title }}</span>
                                        <strong class="text-success">+ ₹ {{ number_format($entry['reward'], 2) }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <hr>

                        @if($user->wallet_balance > 0)
                            <div class="form-check mb-3 p-3" style="background:#f6fbf7;border:1px solid #d7ecdd;border-radius:8px;">
                                <input class="form-check-input" type="checkbox" name="use_wallet" value="1" id="use-wallet" {{ old('use_wallet') ? 'checked' : '' }}>
                                <label class="form-check-label" for="use-wallet">
                                    <i class="far fa-wallet text-success"></i>
                                    Use wallet balance
                                    <strong>(₹ {{ number_format($user->wallet_balance, 2) }} available)</strong>
                                </label>
                            </div>
                        @endif

                        <ul>
                            <li>
                                <strong>Total Items:</strong>
                                <span>{{ $summary['total_qty'] }}</span>
                            </li>
                            <li>
                                <strong>Sub Total:</strong>
                                <span>₹ {{ number_format($summary['sub_total'], 2) }}</span>
                            </li>
                            @if($user->wallet_balance > 0)
                                <li id="wallet-discount-row" style="display:none;">
                                    <strong>Wallet Applied:</strong>
                                    <span id="wallet-discount-amount">- ₹ 0.00</span>
                                </li>
                            @endif
                            <li class="cart-total">
                                <strong>Total Pay:</strong>
                                <span id="final-total-amount">₹ {{ number_format($summary['total'], 2) }}</span>
                            </li>
                        </ul>

                        <div class="d-flex align-items-center gap-2 p-3 mb-3" style="background:#f8f9fa;border-radius:8px;">
                            <i class="far fa-money-bill-wave" style="color:#EE7D21;font-size:20px;"></i>
                            <div>
                                <strong class="d-block">Cash on Delivery</strong>
                                <small class="text-muted">Pay in cash when your order is delivered.</small>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="theme-btn" id="place-order-btn">
                                Place Order <i class="far fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Same as billing toggle — disable/enable shipping fields
    const sameAsBilling   = document.getElementById('same-as-billing');
    const shippingFields  = document.getElementById('shipping-fields');

    function toggleShipping() {
        const inputs = shippingFields.querySelectorAll('input, textarea');
        inputs.forEach(el => {
            if (sameAsBilling.checked) {
                el.closest('.form-group').style.opacity = '0.5';
            } else {
                el.closest('.form-group').style.opacity = '1';
            }
        });
    }

    sameAsBilling.addEventListener('change', toggleShipping);
    toggleShipping(); // init

    // Live-preview wallet deduction (server recalculates the real amount on submit)
    const useWalletCheckbox = document.getElementById('use-wallet');
    if (useWalletCheckbox) {
        const walletBalance   = {{ (float) $user->wallet_balance }};
        const orderTotal      = {{ (float) $summary['total'] }};
        const discountRow     = document.getElementById('wallet-discount-row');
        const discountAmount  = document.getElementById('wallet-discount-amount');
        const finalTotal      = document.getElementById('final-total-amount');

        useWalletCheckbox.addEventListener('change', function () {
            const applied = Math.min(walletBalance, orderTotal);
            if (this.checked) {
                discountRow.style.display = '';
                discountAmount.textContent = '- ₹ ' + applied.toFixed(2);
                finalTotal.textContent = '₹ ' + (orderTotal - applied).toFixed(2);
            } else {
                discountRow.style.display = 'none';
                finalTotal.textContent = '₹ ' + orderTotal.toFixed(2);
            }
        });
    }

    // Prevent double submit
    document.getElementById('checkout-form').addEventListener('submit', function () {
        const btn = document.getElementById('place-order-btn');
        btn.disabled   = true;
        btn.innerHTML  = '<span class="far fa-spinner fa-spin"></span> Placing Order...';
    });
</script>
@endpush