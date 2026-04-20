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
						<h4>Shop Details</h4>
					</div>
					<ul>
						<li><a href="index.html"><i class="bi bi-house-door-fill"></i> Home </a></li>
						<li class="rotates"><i class="bi bi-slash-lg"></i>Shop Details</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!--==================================================-->
<!-- End buddy Breadcumb Area -->
<!--==================================================-->




<!--==================================================-->
<!-- StartGardenic Shop Section  -->
<!--==================================================-->
<div class="shop-detials">
	<div class="container">		
		<div class="row align-items-center">
			<div class="col-lg-6 col-md-6">
				<!-- / tab -->
				<div class="tab style-three">

    <!-- MAIN IMAGE (Thumbnail first) -->
    <div class="tab_content">

        {{-- Thumbnail --}}
        @if($product->getFirstMediaUrl('product_thumbnail'))
            <div class="tabs_item">
                <img src="{{ $product->getFirstMediaUrl('product_thumbnail') }}"
                     alt="{{ $product->name }}">
            </div>
        @endif

        {{-- Gallery Images --}}
        @forelse($product->getMedia('product_gallery') as $media)
            <div class="tabs_item">
                <img src="{{ $media->getUrl() }}" alt="{{ $product->name }}">
            </div>
        @empty
            @if(!$product->getFirstMediaUrl('product_thumbnail'))
                <div class="tabs_item">
                    <img src="{{ asset('assets/images/default.png') }}" alt="No Image">
                </div>
            @endif
        @endforelse

    </div>

    <!-- THUMBNAILS -->
    <ul class="tabs">

        {{-- Thumbnail --}}
        @if($product->getFirstMediaUrl('product_thumbnail'))
            <li>
                <a href="#">
                    <img src="{{ $product->getFirstMediaUrl('product_thumbnail') }}"
                         width="80" alt="">
                </a>
            </li>
        @endif

        {{-- Gallery --}}
        @foreach($product->getMedia('product_gallery') as $media)
            <li>
                <a href="#">
                    <img src="{{ $media->getUrl() }}" width="80" alt="">
                </a>
            </li>
        @endforeach

    </ul>

</div>
			</div>	
			<div class="col-lg-6 col-md-6">
				<div class="shop-dtls-info">
					<!-- category title -->
					<div class="category-title">
						<h2>{{ $product->name }}</h2>
					</div>
					<!-- category icon -->
				
				
					
				

                  <table class="category-table">

    {{-- PRICE --}}
    <tr>
        <td class="table-title"> Price </td>
        <td class="table-text">
            ₹ {{ number_format($product->base_price, 2) }}

            @if($product->sale_type == 'tray')
                <small>(Per Tray)</small>
            @elseif($product->sale_type == 'piece')
                <small>(Per Bird)</small>
            @elseif($product->sale_type == 'weight')
                <small>(Per Kg)</small>
            @endif
        </td>
    </tr>

    {{-- SKU --}}
    <tr>
        <td class="table-title"> SKU </td>
        <td class="table-text"> {{ $product->slug }} </td>
    </tr>

    {{-- CATEGORY --}}
    <tr>
        <td class="table-title"> Category </td>
        <td class="table-text"> {{ $product->category->name ?? '-' }} </td>
    </tr>

    {{-- TAGS --}}
    <tr>
        <td class="table-title"> Tags </td>
        <td class="table-text">
            @forelse($product->tags as $tag)
                {{ $tag->name }}@if(!$loop->last), @endif
            @empty
                -
            @endforelse
        </td>
    </tr>

    {{-- SALE TYPE --}}
    <tr>
        <td class="table-title"> Sale Type </td>
        <td class="table-text">
            @if($product->sale_type == 'tray')
                Tray (30 Eggs)
            @elseif($product->sale_type == 'piece')
                Per Piece (Hen)
            @elseif($product->sale_type == 'weight')
                Per Kg
            @endif
        </td>
    </tr>

    {{-- BULK PRICING --}}
    <tr>
        <td class="table-title"> Bulk Pricing </td>
        <td class="table-text">

            @forelse($product->bulkPrices as $bulk)
                <div>
                    {{ $bulk->min_qty }}+ Qty → ₹{{ number_format($bulk->price, 2) }} /per {{ $product->sale_type == 'tray' ? 'Tray' : ($product->sale_type == 'piece' ? 'Piece' : 'Kg') }}
                </div>
            @empty
                No bulk pricing
            @endforelse

        </td>
    </tr>

</table> <!-- category table End -->
					<div class="category-count-button">

    <!-- Quantity -->
    <div class="quantity">
        <div class="cart-plus-minus">
            <input 
                id="qty-{{ $product->id }}"
                class="cart-plus-minus-box" 
                value="1"
                type="number"
                min="1"
            >
            <div class="dec ctnbutton">-</div>
            <div class="inc ctnbutton">+</div>
        </div>
    </div>

    <!-- Add to Cart -->
    <div class="category-button">
        <a href="#"
           class="add-to-cart-btn"
           data-id="{{ $product->id }}">
            Add to Cart <i class="bi bi-cart3"></i>
        </a>
    </div>

</div>


<script>
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {

    btn.addEventListener('click', function(e) {
        e.preventDefault();

        let productId = this.dataset.id;

        let qtyInput = document.getElementById('qty-' + productId);
        let qty = qtyInput ? parseInt(qtyInput.value) : 1;

        if (!qty || qty <= 0) {
            alert('Please enter valid quantity');
            return;
        }

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                product_id: productId,
                qty: qty
            })
        })
        .then(res => res.json())
        .then(() => {
            alert('Added to cart 🛒');
        })
        .catch(() => {
            alert('Error adding to cart');
        });

    });

});
</script>
					<!-- category table -->
					
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="appoinment-tab">
					<!-- / tab -->
					<div class="tab">
						<!-- / tabs -->
						<ul class="tabs">
							<li><a href="#">Description</a></li>
							<li><a href="#">Additional Info </a></li>
							<li><a href="#">Review (2)</a></li>
						</ul> 
						<div class="tab_content">
							<!-- / tabs_item -->
							<div class="tabs_item">
								<!-- post comment -->
								<div class="post-comment-description">
									
                                        {{ $product->description }}
                                   
								</div>
							</div> 
							<!-- / tabs_item -->
							<div class="tabs_item">
    <table class="tab-items-table">

        <tr>
            <td class="table-title">Product Name</td>
            <td class="table-text">: {{ $product->name }}</td>
        </tr>

        <tr class="tabs-bg">
            <td class="table-title">Category</td>
            <td class="table-text">: {{ $product->category->name ?? 'N/A' }}</td>
        </tr>

        <tr>
            <td class="table-title">Price</td>
            <td class="table-text">: ₹{{ number_format($product->price, 2) }}</td>
        </tr>

        <tr class="tabs-bg">
            <td class="table-title">Bulk Price</td>
            <td class="table-text">
                : {{ $product->bulk_price ? '₹'.number_format($product->bulk_price, 2) : 'N/A' }}
            </td>
        </tr>

        <tr>
            <td class="table-title">Minimum Order Qty</td>
            <td class="table-text">: {{ $product->min_order_qty }}</td>
        </tr>

        <tr class="tabs-bg">
            <td class="table-title">Stock</td>
            <td class="table-text">: {{ $product->stock }}</td>
        </tr>

        <tr>
            <td class="table-title">Type</td>
            <td class="table-text">: {{ $product->type ?? 'N/A' }}</td>
        </tr>

        <tr class="tabs-bg">
            <td class="table-title">Tags</td>
            <td class="table-text">
                : {{ $product->tags->pluck('name')->implode(', ') ?: 'N/A' }}
            </td>
        </tr>

        <tr>
            <td class="table-title">Status</td>
            <td class="table-text">
                : {{ $product->status ? 'Active' : 'Inactive' }}
            </td>
        </tr>

    </table>
</div>
							<!-- / tabs_item -->
							<div class="tabs_item">
								<!-- post comment -->
							
								
							</div> 
						</div> <!-- / tab_content -->
					</div> 
				</div>
			</div>
			<div class="col-lg-12">
    <div class="product-item-title">
        <h2>Related Products</h2>
    </div>
</div>

<div class="row">

@forelse($relatedProducts as $item)
    <div class="col-lg-3 col-md-6">
        <div class="product_single_item">

            <div class="product_thumb">
                <a href="{{ route('shop.show', $item->slug) }}">
                    <img 
                        src="{{ $item->getFirstMediaUrl('product_images') ?: asset('assets/images/default.png') }}" 
                        alt="{{ $item->name }}"
                    >
                </a>

                <div class="buddy_btn_home_three product_btn">
                    <a href="#" data-id="{{ $item->id }}">
                        Add to cart 
                        <i class="flaticon flaticon-right-arrow"></i>
                    </a>
                </div>
            </div>

            <div class="product_content">

                <div class="product_star">
                    <i class="fa fa-star active"></i>
                    <i class="fa fa-star active"></i>
                    <i class="fa fa-star active"></i>
                    <i class="fa fa-star active"></i>
                    <i class="fa fa-star"></i>
                </div>

                <h3>
                    <a href="{{ route('shop.show', $item->slug) }}">
                        {{ $item->name }}
                    </a>
                </h3>

                <p>₹{{ number_format($item->price, 2) }}</p>

            </div>

        </div>
    </div>

@empty
    <div class="col-12 text-center">
        <p>No related products found</p>
    </div>
@endforelse

</div>
		</div>
	</div>
</div>
<!--==================================================-->
<!-- EndGardenic Shop Section  -->
<!--==================================================-->


@endsection