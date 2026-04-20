@extends('custom.master')
@section('content')

 <!-- breadcrumb -->
        <div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/breadcrumb.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Our Shop</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="index.html">Home</a></li>
                    <li class="active">Our Shop</li>
                </ul>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- shop area -->
        <div class="shop-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
    <div class="shop-sidebar">

        <form method="GET">

            <!-- 🔍 Search -->
            <div class="shop-widget">
                <div class="shop-search-form">
                    <h4 class="shop-widget-title">Search</h4>
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control" 
                            placeholder="Search..."
                        >
                        <button type="submit"><i class="far fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- 📂 Category -->
            <div class="shop-widget">
                <h4 class="shop-widget-title">Category</h4>
                <ul>
                    @foreach($categories as $category)
                        <li>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="category[]" 
                                    value="{{ $category->id }}"
                                    id="cat{{ $category->id }}"
                                    {{ in_array($category->id, request('category', [])) ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="cat{{ $category->id }}">
                                    {{ $category->name }}
                                </label>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- 💰 Price Range -->
            <div class="shop-widget">
                <h4 class="shop-widget-title">Price Range</h4>
                <div class="d-flex gap-2">
                    <input 
                        type="number" 
                        name="min_price" 
                        class="form-control"
                        placeholder="Min"
                        value="{{ request('min_price') }}"
                    >
                    <input 
                        type="number" 
                        name="max_price" 
                        class="form-control"
                        placeholder="Max"
                        value="{{ request('max_price') }}"
                    >
                </div>
            </div>

            <!-- 🥚 Type Filter -->
            <div class="shop-widget">
                <h4 class="shop-widget-title">Product Type</h4>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="egg"
                        {{ request('type')=='egg' ? 'checked' : '' }}>
                    <label class="form-check-label">Egg</label>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" value="hen"
                        {{ request('type')=='hen' ? 'checked' : '' }}>
                    <label class="form-check-label">Hen</label>
                </div>
            </div>

            <!-- 🔘 Apply Button -->
            <div class="shop-widget">
                <button type="submit" class="theme-btn w-100">
                    Apply Filters <i class="far fa-filter"></i>
                </button>
            </div>

        </form>

        <!-- 🎯 Banner -->
        <div class="widget-banner mt-30 mb-50" 
             style="background-image:url('{{ asset('assets/img/widget/banner.jpg') }}')">
            <div class="banner-content">
                <h3>Get <span>35% Off</span> On Poultry Chicken</h3>
                <a href="#" class="theme-btn">
                    Buy Now <i class="far fa-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>
</div>
                  <div class="col-lg-8">
    
    {{-- Top Sort --}}
    <div class="col-md-12">
        <div class="shop-sort d-flex justify-content-between align-items-center">
            <h5>
                Showing {{ $products->count() }} Results
            </h5>

            <div class="shop-sort-box">
                <form method="GET">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="">Sort By Default</option>
                        <option value="latest" {{ request('sort')=='latest'?'selected':'' }}>Latest</option>
                        <option value="low" {{ request('sort')=='low'?'selected':'' }}>Low Price</option>
                        <option value="high" {{ request('sort')=='high'?'selected':'' }}>High Price</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="row align-items-center">
        @forelse($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="shop-item">
                    
                    <div class="shop-item-img">
                        <span class="shop-item-sale">Sale</span>

                        {{-- Product Image (Spatie Media) --}}
                        <img 
                            src="{{ $product->getFirstMediaUrl('product_thumbnail') ?: asset('assets/img/shop/01.png') }}" 
                            alt="{{ $product->name }}"
                        >

                        <div class="shop-item-meta">
                            <a href="#"><i class="far fa-heart"></i></a>
                            <a href="#"><i class="far fa-shopping-cart"></i></a>
                        </div>
                    </div>

                    <div class="shop-item-info">
                        
                        {{-- Sale Type --}}
                        <div class="shop-item-qty">
                            {{ ucfirst($product->sale_type) }}
                        </div>

                        {{-- Title --}}
                        <a href="{{ route('shop.show', $product->slug) }}">
                            <h4 class="shop-item-title">{{ $product->name }}</h4>
                        </a>

                        {{-- Price --}}
                        <div class="shop-item-price">
                            ₹{{ $product->base_price }}
                        </div>

                    </div>

                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <h4>No Products Found</h4>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pagination-area mt-4">
        {{ $products->links() }}
    </div>

</div>
                    
                </div>
            </div>
        </div>
        <!-- shop area end -->


@endsection