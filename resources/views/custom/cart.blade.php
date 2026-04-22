@extends('custom.master')

@section('content')

<div class="shop-cart py-120">
    <div class="container">

        @if(empty($cart))
            <div class="text-center py-5">
                <i class="far fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Your cart is empty</h4>
                <a href="{{ route('shop') }}" class="theme-btn mt-3">Continue Shopping</a>
            </div>
        @else

        <div class="shop-cart-wrapper">
            <div class="table-responsive">
                <table class="table" id="cart-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($cart as $item)
                        <tr id="cart-row-{{ $item['product_id'] }}"
                            data-base-price="{{ $item['base_price'] }}"
                            data-bulk-prices='@json($item["bulk_prices"])'>

                            {{-- Image --}}
                            <td>
                                <img src="{{ $item['image'] ?: asset('assets/img/shop/01.png') }}" width="70">
                            </td>

                            {{-- Name --}}
                            <td>
                                <h5>{{ $item['name'] }}</h5>
                                <small>{{ ucfirst($item['sale_type']) }}</small>

                                @if($item['unit_price'] < $item['base_price'])
                                    <br><span class="badge bg-success">Bulk Price Applied</span>
                                @endif
                            </td>

                            {{-- Price --}}
                            <td>
                                <span id="unit-price-{{ $item['product_id'] }}">
                                    ₹ {{ number_format($item['unit_price'], 2) }}
                                </span>
                            </td>

                            {{-- Quantity --}}
                            <td>
                                <div class="cart-qty-text">
    {{ $item['quantity'] }} <small>{{ ucfirst($item['sale_type']) }}</small>
</div>
                            </td>

                            {{-- Subtotal --}}
                            <td>
                                <span id="subtotal-{{ $item['product_id'] }}">
                                    ₹ {{ number_format($item['subtotal'], 2) }}
                                </span>
                            </td>

                            {{-- Remove --}}
                            <td>
                                <a href="#" class="cart-remove" data-id="{{ $item['product_id'] }}">
                                    <i class="far fa-times"></i>
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 🔥 SUMMARY --}}
            <div class="cart-footer">
                <div class="row justify-content-end">
                    <div class="col-md-6 col-lg-5">

                        <div class="cart-summary">
                            <ul>

                                <li>
                                    <strong>Sub Total:</strong>
                                    <span id="summary-subtotal">
                                        ₹ {{ number_format($summary['sub_total'], 2) }}
                                    </span>
                                </li>

                              

                                @if($summary['discount'] > 0)
                                <li>
                                    <strong>Offer Discount:</strong>
                                    <span class="text-success">
                                        - ₹ {{ number_format($summary['discount'], 2) }}
                                    </span>
                                </li>

                                <li>
                                    <small class="text-success">
                                        🎉 {{ $summary['offer'] }} applied
                                    </small>
                                </li>
                                @endif

                                <li class="cart-total">
                                    <strong>Total:</strong>
                                    <span id="summary-total">
                                        ₹ {{ number_format($summary['total'], 2) }}
                                    </span>
                                </li>

                            </ul>

                            <div class="text-end mt-40">
                                <a href="{{ route('checkout.index') }}" class="theme-btn">
                                    Checkout Now →
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

<script>

const UPDATE_URL = "{{ route('cart.update') }}";
const REMOVE_URL = "{{ route('cart.remove') }}";
const TOKEN = "{{ csrf_token() }}";

function updateSummary(summary) {
    document.getElementById('summary-subtotal').textContent = '₹ ' + summary.sub_total.toFixed(2);
    document.getElementById('summary-total').textContent = '₹ ' + summary.total.toFixed(2);
}

function resolvePrice(base, bulk, qty) {
    let price = base;
    bulk.forEach(b => {
        if (qty >= b.min_qty) price = b.price;
    });
    return price;
}

function qtyChange(id) {
    let input = document.getElementById('qty-' + id);
    let qty = parseInt(input.value) || 1;

    let row = document.getElementById('cart-row-' + id);
    let base = parseFloat(row.dataset.basePrice);
    let bulk = JSON.parse(row.dataset.bulkPrices);

    let price = resolvePrice(base, bulk, qty);

    document.getElementById('unit-price-' + id).textContent = '₹ ' + price.toFixed(2);
    document.getElementById('subtotal-' + id).textContent = '₹ ' + (price * qty).toFixed(2);

    fetch(UPDATE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': TOKEN
        },
        body: JSON.stringify({ product_id: id, quantity: qty })
    })
    .then(res => res.json())
    .then(data => updateSummary(data.summary));
}

// plus
document.querySelectorAll('.cart-plus').forEach(btn => {
    btn.onclick = () => {
        let id = btn.dataset.id;
        let input = document.getElementById('qty-' + id);
        input.value = (parseInt(input.value) || 1) + 1;
        qtyChange(id);
    };
});

// minus
document.querySelectorAll('.cart-minus').forEach(btn => {
    btn.onclick = () => {
        let id = btn.dataset.id;
        let input = document.getElementById('qty-' + id);
        if (input.value > 1) {
            input.value--;
            qtyChange(id);
        }
    };
});

// remove
document.querySelectorAll('.cart-remove').forEach(btn => {
    btn.onclick = (e) => {
        e.preventDefault();
        let id = btn.dataset.id;

        fetch(REMOVE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': TOKEN
            },
            body: JSON.stringify({ product_id: id })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('cart-row-' + id).remove();
            updateSummary(data.summary);
            if (data.cart_count === 0) location.reload();
        });
    };
});

</script>

@endsection


