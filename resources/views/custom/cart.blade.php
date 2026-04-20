@extends('custom.master')

@section('content')

{{-- Breadcrumb --}}
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/breadcrumb.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Shop Cart</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Shop Cart</li>
        </ul>
    </div>
</div>

{{-- Cart Area --}}
<div class="shop-cart py-120">
    <div class="container">

        @if(empty($cart))
            <div class="text-center py-5">
                <i class="far fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Your cart is empty</h4>
                <a href="{{ route('shop.index') }}" class="theme-btn mt-3">Continue Shopping</a>
            </div>
        @else
        <div class="shop-cart-wrapper">
            <div class="table-responsive">
                <table class="table" id="cart-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Sub Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                        <tr id="cart-row-{{ $item['product_id'] }}"
                            data-product-id="{{ $item['product_id'] }}"
                            data-base-price="{{ $item['base_price'] }}"
                            data-bulk-prices="{{ json_encode($item['bulk_prices']) }}"
                            data-sale-type="{{ $item['sale_type'] }}">

                            {{-- Image --}}
                            <td>
                                <div class="cart-img">
                                    <img src="{{ $item['image'] ?: asset('assets/img/shop/01.png') }}"
                                         alt="{{ $item['name'] }}" width="70">
                                </div>
                            </td>

                            {{-- Name --}}
                            <td>
                                <h5>{{ $item['name'] }}</h5>
                                <small class="text-muted">{{ ucfirst($item['sale_type']) }}</small>
                                @if($item['unit_price'] < $item['base_price'])
                                    <br><span class="badge bg-success">Bulk Price Applied</span>
                                @endif
                            </td>

                            {{-- Unit Price --}}
                            <td>
                                <div class="cart-price">
                                    <span id="unit-price-{{ $item['product_id'] }}">
                                        ₹ {{ number_format($item['unit_price'], 2) }}
                                    </span>
                                    @if($item['unit_price'] < $item['base_price'])
                                        <br><del class="text-muted small">₹ {{ number_format($item['base_price'], 2) }}</del>
                                    @endif
                                </div>
                            </td>

                            {{-- Quantity --}}
                            <td>
                                <div class="cart-qty">
                                    <button class="minus-btn cart-minus" data-id="{{ $item['product_id'] }}" type="button">
                                        <i class="fal fa-minus"></i>
                                    </button>
                                    <input class="quantity cart-qty-input"
                                           id="qty-{{ $item['product_id'] }}"
                                           type="text"
                                           value="{{ $item['quantity'] }}"
                                           data-id="{{ $item['product_id'] }}">
                                    <button class="plus-btn cart-plus" data-id="{{ $item['product_id'] }}" type="button">
                                        <i class="fal fa-plus"></i>
                                    </button>
                                </div>
                            </td>

                            {{-- Subtotal --}}
                            <td>
                                <div class="cart-sub-total">
                                    <span id="subtotal-{{ $item['product_id'] }}">
                                        ₹ {{ number_format($item['subtotal'], 2) }}
                                    </span>
                                </div>
                            </td>

                            {{-- Remove --}}
                            <td>
                                <a href="#" class="cart-remove" data-id="{{ $item['product_id'] }}" title="Remove">
                                    <i class="far fa-times"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Cart Footer — only Total, no VAT/Coupon --}}
            <div class="cart-footer">
                <div class="row justify-content-end">
                    <div class="col-md-6 col-lg-5">
                        <div class="cart-summary">
                            <ul>
                                <li>
                                    <strong>Sub Total:</strong>
                                    <span id="summary-subtotal">₹ {{ number_format($summary['sub_total'], 2) }}</span>
                                </li>
                                <li class="cart-total">
                                    <strong>Total:</strong>
                                    <span id="summary-total">₹ {{ number_format($summary['total'], 2) }}</span>
                                </li>
                            </ul>
                            <div class="text-end mt-40">
                                <a href="{{ route('checkout.index') }}" class="theme-btn">
                                    Checkout Now <i class="far fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    const CART_UPDATE_URL = "{{ route('cart.update') }}";
    const CART_REMOVE_URL = "{{ route('cart.remove') }}";
    const CSRF_TOKEN      = "{{ csrf_token() }}";

    function getRowData(productId) {
        const row        = document.getElementById('cart-row-' + productId);
        const basePrice  = parseFloat(row.dataset.basePrice);
        const bulkPrices = JSON.parse(row.dataset.bulkPrices);
        return { row, basePrice, bulkPrices };
    }

    function resolveUnitPrice(basePrice, bulkPrices, qty) {
        let price = basePrice;
        bulkPrices.forEach(tier => {
            if (qty >= tier.min_qty) price = tier.price;
        });
        return price;
    }

    function updateRowUI(productId, qty, unitPrice) {
        document.getElementById('unit-price-' + productId).textContent = '₹ ' + parseFloat(unitPrice).toFixed(2);
        document.getElementById('subtotal-'   + productId).textContent = '₹ ' + (unitPrice * qty).toFixed(2);
    }

    function updateSummaryUI(summary) {
        document.getElementById('summary-subtotal').textContent = '₹ ' + parseFloat(summary.sub_total).toFixed(2);
        document.getElementById('summary-total').textContent    = '₹ ' + parseFloat(summary.total).toFixed(2);
    }

    // ── Qty changed ────────────────────────────────────────────────────────────
    let debounceTimers = {};

    function qtyChanged(productId) {
        const input = document.getElementById('qty-' + productId);
        let qty     = parseInt(input.value);

        if (isNaN(qty) || qty < 1) { qty = 1; input.value = 1; }

        const { basePrice, bulkPrices } = getRowData(productId);
        const unitPrice = resolveUnitPrice(basePrice, bulkPrices, qty);

        // Instant UI update
        updateRowUI(productId, qty, unitPrice);

        // Debounced server sync
        clearTimeout(debounceTimers[productId]);
        debounceTimers[productId] = setTimeout(() => {
            fetch(CART_UPDATE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept'      : 'application/json',
                },
                body: JSON.stringify({ product_id: parseInt(productId), quantity: qty })
            })
            .then(r => r.json())
            .then(data => { if (data.success) updateSummaryUI(data.summary); });
        }, 500);
    }

    // ── Plus ───────────────────────────────────────────────────────────────────
    document.querySelectorAll('.cart-plus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id    = this.dataset.id;
            const input = document.getElementById('qty-' + id);
            input.value = (parseInt(input.value) || 1) + 1;
            qtyChanged(id);
        });
    });

    // ── Minus ──────────────────────────────────────────────────────────────────
    document.querySelectorAll('.cart-minus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id  = this.dataset.id;
            const input = document.getElementById('qty-' + id);
            const val = parseInt(input.value) || 1;
            if (val > 1) { input.value = val - 1; qtyChanged(id); }
        });
    });

    // ── Manual input — fire on blur or Enter (not on every keypress) ───────────
    document.querySelectorAll('.cart-qty-input').forEach(input => {
        input.addEventListener('change', function () { qtyChanged(this.dataset.id); });
        input.addEventListener('keyup',  function (e) {
            if (e.key === 'Enter') qtyChanged(this.dataset.id);
        });
    });

    // ── Remove ─────────────────────────────────────────────────────────────────
    document.querySelectorAll('.cart-remove').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productId = this.dataset.id;
            if (!confirm('Remove this item from cart?')) return;

            fetch(CART_REMOVE_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept'      : 'application/json',
                },
                body: JSON.stringify({ product_id: parseInt(productId) })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('cart-row-' + productId)?.remove();
                    updateSummaryUI(data.summary);
                    if (data.cart_count === 0) location.reload();
                }
            });
        });
    });
</script>
@endpush