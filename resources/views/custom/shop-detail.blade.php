@extends('custom.master')
@section('content')

  <!-- breadcrumb -->
       <div class="site-breadcrumb" style="background: url('{{ asset('assets/img/breadcrumb/breadcrumb.jpg') }}')">
    <div class="container">

        {{-- Title --}}
        <h2 class="breadcrumb-title">
            {{ $product->name ?? 'Shop' }}
        </h2>

        {{-- Breadcrumb --}}
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>

            <li>
                <a href="{{ route('shop') }}">Shop</a>
            </li>

            <li class="active">
                {{ $product->name ?? 'Product' }}
            </li>
        </ul>

    </div>
</div>
        <!-- breadcrumb end -->


        <!-- shop single -->
     
<div class="shop-item-single py-120">
    <div class="container">
        <div class="row">
 
            {{-- Product Gallery --}}
            <div class="col-lg-5">
    <div class="product-gallery">

        @php
            $thumbnail = $product->getFirstMediaUrl('product_thumbnail');
            $gallery   = $product->getMedia('product_gallery');
        @endphp

        {{-- 🔥 MAIN IMAGE --}}
        <div class="main-image mb-3">
            <img id="mainProductImage"
                 src="{{ $thumbnail ?: ($gallery->first()?->getUrl() ?? asset('assets/img/shop/01.png')) }}"
                 alt="{{ $product->name }}">
        </div>

        {{-- 🔽 THUMBNAILS --}}
        <div class="thumbs d-flex flex-wrap gap-2">

            {{-- Thumbnail --}}
            @if($thumbnail)
                <img src="{{ $thumbnail }}"
                     class="thumb active"
                     onclick="changeImage(this)">
            @endif

            {{-- Gallery --}}
            @foreach($gallery as $media)
                <img src="{{ $media->getUrl() }}"
                     class="thumb"
                     onclick="changeImage(this)">
            @endforeach

            {{-- Fallback --}}
            @if(!$thumbnail && $gallery->isEmpty())
                <img src="{{ asset('assets/img/shop/01.png') }}"
                     class="thumb active"
                     onclick="changeImage(this)">
            @endif

        </div>

    </div>
</div>
<style>
    /* main image */
.product-gallery .main-image {
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    background: #f9f9f9;
}

.product-gallery .main-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    transition: 0.3s;
}

/* hover zoom */
.product-gallery .main-image img:hover {
    transform: scale(1.05);
}

/* thumbnails */
.product-gallery .thumbs {
    margin-top: 10px;
}

.product-gallery .thumb {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: 0.3s;
}

/* active thumb */
.product-gallery .thumb.active {
    border-color: #EE7D21;
}

/* hover */
.product-gallery .thumb:hover {
    border-color: #EE7D21;
}
</style>

<script>
function changeImage(el) {
    document.getElementById('mainProductImage').src = el.src;

    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}
</script>
 
            {{-- Product Info --}}
            <div class="col-lg-6">
                <div class="single-item-info">
 
                    <h4 class="single-item-title">{{ $product->name }}</h4>
 
                    <div class="single-item-price">
                        @php
                            $basePrice = $product->base_price;
 
                            // Check if there's a bulk price lower than base (first tier)
                            $lowestBulk = $product->bulkPrices->first();
                        @endphp
 
                        @if($lowestBulk && $lowestBulk->price < $basePrice)
                            <h4>
                                <del>₹ {{ number_format($basePrice, 2) }}</del>
                                <span>₹ {{ number_format($lowestBulk->price, 2) }}</span>
                            </h4>
                        @else
                            <h4><span>₹ {{ number_format($basePrice, 2) }}</span></h4>
                        @endif
                    </div>
 
                    @if($product->description)
                        <p class="mb-4">{{ $product->description }}</p>
                    @endif
 
                    <div class="single-item-content">
                        <h5>Stock:
                            <span>
                                @if($product->stock > 0)
                                    Available ({{ $product->stock }} {{ $product->sale_type ?? 'units' }})
                                @else
                                    Out of Stock
                                @endif
                            </span>
                        </h5>
                        <h5>SKU: <span>{{ strtoupper(Str::limit($product->slug, 8, '')) }}</span></h5>
                        <h5>Type: <span>{{ ucfirst($product->type) }}</span></h5>
                        <h5>Sale By: <span>{{ ucfirst($product->sale_type) }}</span></h5>
                    </div>
                      <div class="single-item-content mt-3">
                        @if($product->category)
                            <h5>Category: <span>{{ $product->category->name }}</span></h5>
                        @endif
 
                        @if($product->tags->isNotEmpty())
                            <h5>Tags:
                                <span>
                                    {{ $product->tags->pluck('name')->implode(', ') }}
                                </span>
                            </h5>
                        @endif
                    </div>
 
                   {{-- ===== BULK PRICE TABLE ===== --}}
@if($product->bulkPrices->isNotEmpty())
    <div class="bulk-price-table my-3">
       

    <h5 class="mb-3">Bulk Pricing</h5>

    <div class="table-responsive">
        <table class="table table-borderless bulk-table">

            <thead>
                <tr>
                    <th>Quantity</th>
                    <th class="text-end">Price / {{ ucfirst($product->sale_type) }}</th>
                </tr>
            </thead>

            <tbody>

                {{-- Base Price --}}
                <tr class="bulk-row active">
                    <td>
                        <span class="qty-badge">1+</span>
                    </td>
                    <td class="text-end">
                        <strong>₹ {{ number_format($product->base_price, 2) }}</strong>
                        <span class="tag">Base</span>
                    </td>
                </tr>

                {{-- Bulk Prices --}}
                @foreach($product->bulkPrices as $bulk)
                <tr class="bulk-row">
                    <td>
                        <span class="qty-badge">{{ $bulk->min_qty }}+</span>
                    </td>
                    <td class="text-end">
                        ₹ {{ number_format($bulk->price, 2) }}

                        {{-- 🔥 Save badge --}}
                        @if($product->base_price > $bulk->price)
                            <span class="save-badge">
                                Save ₹ {{ $product->base_price - $bulk->price }}
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach

            </tbody>

        </table>
    </div>
</div>
@endif
<style>
    /* table */
.bulk-table {
    width: 100%;
}

.bulk-table thead th {
    font-size: 14px;
    color: #777;
    border-bottom: 1px solid #eee;
}

/* rows */
.bulk-row {
    transition: 0.3s;
    border-bottom: 1px solid #f2f2f2;
}

.bulk-row:hover {
    background: #fff8f3;
}

/* active row */
.bulk-row.active {
    background: #fff3e8;
}

/* quantity badge */
.qty-badge {
    background: #f1f1f1;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

/* base tag */
.tag {
    margin-left: 6px;
    font-size: 11px;
    color: #999;
}

/* save badge */
.save-badge {
    margin-left: 8px;
    background: #e6fff1;
    color: #28a745;
    font-size: 11px;
    padding: 3px 6px;
    border-radius: 5px;
    font-weight: 600;
}
</style>
    


 
                 
{{-- ===== ADD TO CART SECTION ===== --}}
@if($product->stock > 0)
    <div class="single-item-action">
        <h5 class="title">Quantity:</h5>
       <div class="cart-qty-box">

    <button type="button" class="qty-btn minus-btn">
        <i class="fal fa-minus"></i>
    </button>

    <input type="number"
           id="qty-input"
           class="qty-input"
           value="1"
           min="1"
           max="{{ $product->stock }}">

    <button type="button" class="qty-btn plus-btn">
        <i class="fal fa-plus"></i>
    </button>

</div>


<style>
    .cart-qty-box {
    display: inline-flex;
    align-items: center;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

/* input */
.qty-input {
    width: 60px;
    height: 45px;
    border: none;
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    outline: none;
}

/* buttons */
.qty-btn {
    width: 45px;
    height: 45px;
    border: none;
    background: #f8f8f8;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.qty-btn:hover {
    background: #EE7D21;
    color: #fff;
}

/* remove arrows from input */
.qty-input::-webkit-inner-spin-button,
.qty-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
       
 
        <div class="item-single-btn-area">
            <button class="theme-btn" id="add-to-cart-btn"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-base-price="{{ $product->base_price }}">
                <span class="far fa-shopping-cart"></span> Add to Cart
            </button>
        </div>
    </div>

     {{-- Live price display --}}
        <div class="live-price my-2">
            <span id="per-unit-label" class="text-muted small">
                ₹ {{ number_format($product->base_price, 2) }} × 1 {{ $product->sale_type }}
            </span><br>
            <strong>Total: ₹ <span id="live-price">{{ number_format($product->base_price, 2) }}</span></strong>
        </div>
@else
    <div class="single-item-action">
        <button class="theme-btn" disabled>Out of Stock</button>
    </div>
@endif
 
                  
 
                    <hr>
 
                    <div class="single-item-share">
                        <span>Share:</span>
                        @php $shareUrl = urlencode(request()->fullUrl()); @endphp
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($product->name) }}" target="_blank"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $shareUrl }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}" target="_blank"><i class="fab fa-pinterest-p"></i></a>
                    </div>
 
                </div>
            </div>
        </div>
 
        {{-- Description / Additional Info / Reviews Tabs --}}
        <div class="single-item-details mt-5">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-tab1" data-bs-toggle="tab" data-bs-target="#tab1"
                        type="button" role="tab" aria-controls="tab1" aria-selected="true">Description</button>
                    <button class="nav-link" id="nav-tab2" data-bs-toggle="tab" data-bs-target="#tab2"
                        type="button" role="tab" aria-controls="tab2" aria-selected="false">Additional Info</button>
                    <button class="nav-link" id="nav-tab3" data-bs-toggle="tab" data-bs-target="#tab3"
                        type="button" role="tab" aria-controls="tab3" aria-selected="false">Reviews</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
 
                {{-- Description Tab --}}
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="nav-tab1">
                    <div class="single-item-desc">
                        @if($product->description)
                            <p>{{ $product->description }}</p>
                        @else
                            <p>No description available for this product.</p>
                        @endif
                    </div>
                </div>
 
                {{-- Additional Info Tab --}}
                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="nav-tab2">
                    <div class="single-additional-info">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Product Name</th>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td>{{ ucfirst($product->type) }}</td>
                                </tr>
                                <tr>
                                    <th>Sale Type</th>
                                    <td>{{ ucfirst($product->sale_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Base Price</th>
                                    <td>₹ {{ number_format($product->base_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Stock</th>
                                    <td>{{ $product->stock > 0 ? $product->stock . ' ' . ucfirst($product->sale_type) : 'Out of Stock' }}</td>
                                </tr>
                                @if($product->tags->isNotEmpty())
                                <tr>
                                    <th>Tags</th>
                                    <td>{{ $product->tags->pluck('name')->implode(', ') }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
 
                {{-- Reviews Tab --}}
                <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="nav-tab3">
                    <div class="single-item-review">
                        <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                    </div>
                </div>
 
            </div>
        </div>
 
        {{-- Related Products --}}
        @if($relatedProducts->isNotEmpty())
        <div class="related-item mt-5">
            <div class="row">
                <div class="col-12 mx-auto">
                    <div class="site-heading">
                        <h2 class="site-title">Related Items</h2>
                    </div>
                </div>
            </div>
 
            <div class="row align-items-center">
                @foreach($relatedProducts as $related)
                    @php
                        $relatedThumb = $related->getFirstMediaUrl('product_thumbnail');
                        $relatedPrice = $related->base_price;
                        $relatedBulk  = $related->bulkPrices->first();
                    @endphp
                    <div class="col-md-6 col-lg-3">
                        <div class="shop-item">
                            <div class="shop-item-img">
                                @if($relatedBulk && $relatedBulk->price < $relatedPrice)
                                    <span class="shop-item-sale">Sale</span>
                                @endif
 
                                <img src="{{ $relatedThumb ?: 'assets/img/shop/01.png' }}" alt="{{ $related->name }}">
 
                                <div class="shop-item-meta">
                                    <a href="#"><i class="far fa-heart"></i></a>
                                    <a href="#"><i class="far fa-shopping-cart"></i></a>
                                </div>
                            </div>
                            <div class="shop-item-info">
                                <div class="shop-item-qty">{{ ucfirst($related->sale_type) }}</div>
                                <a href="{{ route('shop.detail', $related->slug) }}">
                                    <h4 class="shop-item-title">{{ $related->name }}</h4>
                                </a>
                                <div class="shop-item-price">
                                    @if($relatedBulk && $relatedBulk->price < $relatedPrice)
                                        <del>₹ {{ number_format($relatedPrice, 2) }}</del>
                                        ₹ {{ number_format($relatedBulk->price, 2) }}
                                    @else
                                        ₹ {{ number_format($relatedPrice, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
 
    </div>
</div>
 
 <script>
    const bulkPrices = @json(
        $product->bulkPrices->map(fn($b) => [
            'min_qty' => (int) $b->min_qty,
            'price'   => (float) $b->price,
        ])
    );
 
    const basePrice = {{ (float) $product->base_price }};
    const maxStock  = {{ (int) $product->stock }};
    const saleType  = "{{ $product->sale_type }}";
 
    function getUnitPrice(qty) {
        let price = basePrice;
        bulkPrices.forEach(function (tier) {
            if (qty >= tier.min_qty) price = tier.price;
        });
        return price;
    }
 
    function highlightActiveTier(qty) {
        document.querySelectorAll('[id^="bulk-row-"]').forEach(row => {
            row.classList.remove('table-success', 'fw-bold');
        });
        let activeTierQty = 'base';
        bulkPrices.forEach(function (tier) {
            if (qty >= tier.min_qty) activeTierQty = tier.min_qty;
        });
        const activeRow = document.getElementById('bulk-row-' + activeTierQty);
        if (activeRow) activeRow.classList.add('table-success', 'fw-bold');
    }
 
    function updateLivePrice() {
        const qty       = parseInt(document.getElementById('qty-input').value) || 1;
        const unitPrice = getUnitPrice(qty);
        const total     = (unitPrice * qty).toFixed(2);
 
        document.getElementById('live-price').textContent     = total;
        document.getElementById('per-unit-label').textContent =
            '₹ ' + unitPrice.toFixed(2) + ' × ' + qty + ' ' + saleType;
 
        highlightActiveTier(qty);
    }
 
    document.querySelector('.minus-btn')?.addEventListener('click', function () {
        const input = document.getElementById('qty-input');
        const val   = parseInt(input.value) || 1;
        if (val > 1) { input.value = val - 1; updateLivePrice(); }
    });
 
    document.querySelector('.plus-btn')?.addEventListener('click', function () {
        const input = document.getElementById('qty-input');
        const val   = parseInt(input.value) || 1;
        if (val < maxStock) { input.value = val + 1; updateLivePrice(); }
    });
 
    document.getElementById('qty-input')?.addEventListener('input', updateLivePrice);
 
    // ── ADD TO CART (fixed with proper error handling) ────────────────────────
    document.getElementById('add-to-cart-btn')?.addEventListener('click', function () {
        const btn       = this;
        const qty       = parseInt(document.getElementById('qty-input').value) || 1;
        const productId = btn.dataset.productId;
        const unitPrice = getUnitPrice(qty);
 
        // Disable button to prevent double-click
        btn.disabled    = true;
        btn.innerHTML   = '<span class="far fa-spinner fa-spin"></span> Adding...';
 
        fetch("{{ route('cart.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',   // ← important: tells Laravel to return JSON not redirect
            },
            body: JSON.stringify({
                product_id  : parseInt(productId),
                quantity    : qty,
                unit_price  : unitPrice,
                total_price : (unitPrice * qty).toFixed(2),
            })
        })
        .then(function (response) {
            // Log full response for debugging
            console.log('Status:', response.status);
 
            return response.json().then(function (data) {
                return { status: response.status, data: data };
            });
        })
        .then(function ({ status, data }) {
            console.log('Response data:', data);
 
            if (status === 200 && data.success) {
                // ✅ Success
                btn.innerHTML = '<span class="far fa-check"></span> Added!';
                btn.classList.add('btn-success');
 
                // Update cart badge if you have one
                const badge = document.getElementById('cart-count-badge');
                if (badge && data.cart_count !== undefined) {
                    badge.textContent = data.cart_count;
                }
 
                // Reset button after 2s
                setTimeout(function () {
                    btn.disabled  = false;
                    btn.innerHTML = '<span class="far fa-shopping-cart"></span> Add to Cart';
                    btn.classList.remove('btn-success');
                }, 2000);
 
            } else {
                // ❌ Server returned error (validation etc.)
                console.error('Server error:', data);
                alert(data.message ?? 'Could not add to cart. Check console for details.');
                btn.disabled  = false;
                btn.innerHTML = '<span class="far fa-shopping-cart"></span> Add to Cart';
            }
        })
        .catch(function (err) {
            // ❌ Network / parse error
            console.error('Fetch error:', err);
            alert('Network error. Open browser console (F12) and check the error.');
            btn.disabled  = false;
            btn.innerHTML = '<span class="far fa-shopping-cart"></span> Add to Cart';
        });
    });
 
    updateLivePrice(); // init on page load
</script>
        <!-- shop single end -->


@endsection