@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url('{{ asset('assets/img/breadcrumb/breadcrumb.jpg') }}')">
    <div class="container">
        <h2 class="breadcrumb-title">Latest Offers</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Offers</li>
        </ul>
    </div>
</div>

<div class="container py-120">
    <div class="row g-4">

        @forelse($offers as $offer)
        <div class="col-md-6 col-lg-4">

            <div class="offer-card position-relative overflow-hidden">

                {{-- 🔥 Offer Badge --}}
                <span class="offer-badge">
                    @if($offer->reward_type == 'discount')
                        ₹{{ $offer->reward_value }} OFF
                    @else
                        {{ $offer->reward_value }}% OFF
                    @endif
                </span>

                {{-- Image --}}
                <div class="offer-img">
                    <img 
                        src="{{ $offer->getFirstMediaUrl('offer_image') ?: asset('assets/img/shop/01.png') }}" 
                        alt="{{ $offer->title }}"
                    >
                </div>

                {{-- Content --}}
                <div class="offer-content">
                    <h4>{{ $offer->title }}</h4>

                    <p>{{ Str::limit($offer->description, 80) }}</p>

                    <div class="offer-meta">
                        <span>Min: ₹{{ $offer->min_amount }}</span>
                        <span>Valid: {{ \Carbon\Carbon::parse($offer->end_date)->format('d M') }}</span>
                    </div>

                    <a href="{{ route('shop') }}" class="theme-btn btn-sm mt-2">
                        Shop Now <i class="far fa-arrow-right"></i>
                    </a>
                </div>

            </div>

        </div>
        @empty
            <div class="text-center">
                <h4>No Offers Available</h4>
            </div>
        @endforelse

    </div>
</div>

<style>
    .offer-card {
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.offer-card:hover {
    transform: translateY(-8px);
}

.offer-img img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.offer-content {
    padding: 20px;
}

.offer-content h4 {
    font-size: 18px;
    font-weight: 600;
}

.offer-meta {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    margin-top: 10px;
    color: #777;
}

.offer-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: linear-gradient(45deg, #ff6b00, #ff9f43);
    color: #fff;
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 50px;
    font-weight: 600;
}
</style>
@endsection