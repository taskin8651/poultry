@extends('custom.master')
@section('content')


 <!-- hero slider -->
        <div class="hero-section">
            <div class="hero-slider owl-carousel owl-theme">

             @forelse($heroes as $hero)
            @php
                $image = $hero->getFirstMediaUrl('hero_image') 
                          ?: asset('assets/img/slider/slider-1.jpg');
            @endphp
                <div class="hero-single" style="background-image: url('{{ $image }}')">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-7 col-lg-7">
                                <div class="hero-content">
                                    <h6 class="hero-sub-title wow animate__animated animate__fadeInUp"
                                        data-wow-duration="1s" data-wow-delay=".25s">Welcome To Poultry Farm</h6>
                                    <h1 class="hero-title wow animate__animated animate__fadeInUp"
                                        data-wow-duration="1s" data-wow-delay=".50s">
                                        {{ $hero->title }}
                                    </h1>
                                    <p class="wow animate__animated animate__fadeInUp" data-wow-duration="1s"
                                        data-wow-delay=".75s">
                                        {{ $hero->subtitle }}
                                    </p>
                                    <div class="hero-btn wow animate__animated animate__fadeInUp" data-wow-duration="1s"
                                        data-wow-delay="1s">
                                        <a href="{{ $hero->button_link }}" class="theme-btn">
                                            {{ $hero->button_text }}
                                            <i class="far fa-arrow-right"></i>
                                        </a>
                                        <a href="{{ url('/about') }}" class="theme-border-btn">About Us<i
                                                class="far fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty

            {{-- fallback --}}
            <div class="hero-single" style="background-image: url('{{ asset('assets/img/slider/slider-1.jpg') }}')">
                <div class="container">
                    <h2>No Hero Added</h2>
                </div>
            </div>

        @endforelse
            </div>
        </div>
        <!-- hero slider end -->


		
       
<!-- about area -->
<div class="about-area py-120">
    <div class="container">
        <div class="row align-items-center">

            {{-- Image --}}
            <div class="col-lg-6">
                <div class="about-left">
                    <div class="about-img">
                        <img 
                            src="{{ $about?->getFirstMediaUrl('about_image') ?: asset('assets/img/about/01.jpg') }}" 
                            alt="about"
                        >
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-lg-6">
                <div class="about-right">

                    <div class="site-heading mb-3">
                        <span class="site-title-tagline">
                            {{ $about->subtitle ?? 'About Us' }}
                        </span>

                        <h2 class="site-title">
                            {{ $about->title ?? 'Default Title' }}
                        </h2>
                    </div>

                    <p class="about-text">
                        {{ $about->description ?? 'No description available' }}
                    </p>

                    

                </div>
            </div>

        </div>
    </div>
</div>



<div class="container py-50">
    <div class="row g-4">
        <div class="site-heading text-center">
                            <span class="site-title-tagline">Our Offers</span>
                            <h2 class="site-title ">What We Offer</h2>
                            <div class="heading-divider"></div>
                            <p>
                                It is a long established fact that a reader will be distracted by the readable content
                                of a page when looking at its layout.
                            </p>
                        </div>

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



 <!-- product -->
        <div class="product-area py-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline">Products</span>
                            <h2 class="site-title ">Poultry Farm Products</h2>
                            <div class="heading-divider"></div>
                            <p>
                                It is a long established fact that a reader will be distracted by the readable content
                                of a page when looking at its layout.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
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
            </div>
        </div>
        <!-- product end -->


          <!-- testimonial-area -->
<div class="testimonial-area bg py-120">
    <div class="container">

        {{-- Heading --}}
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline">Testimonials</span>
                    <h2 class="site-title">What Client Say's</h2>
                    <div class="heading-divider"></div>
                </div>
            </div>
        </div>

        {{-- Slider --}}
        <div class="testimonial-slider owl-carousel owl-theme">

            @forelse($testimonials as $testimonial)
                <div class="testimonial-single">

                    {{-- Message --}}
                    <div class="testimonial-quote">
                        <div class="testimonial-quote-icon">
                            <img src="{{ asset('assets/img/icon/icon-16.svg') }}" alt="">
                        </div>
                        <p>{{ $testimonial->message }}</p>
                    </div>

                    {{-- Author --}}
                    <div class="testimonial-content">
                        <div class="testimonial-author-img">
                            <img 
                                src="{{ $testimonial->getFirstMediaUrl('testimonial_image') ?: asset('assets/img/testimonial/01.jpg') }}" 
                                alt="{{ $testimonial->name }}"
                            >
                        </div>

                        <div class="testimonial-author-info">
                            <h4>{{ $testimonial->name }}</h4>
                            <p>{{ $testimonial->position }}</p>

                            {{-- Rating --}}
                            <div class="testimonial-rate">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial->rating ? '' : 'text-muted' }}"></i>
                                @endfor
                            </div>

                        </div>
                    </div>

                </div>
            @empty
                <p class="text-center">No testimonials found</p>
            @endforelse

        </div>

    </div>
</div>
<!-- testimonial-area end -->



<!-- service area -->
<div class="service-area bg py-120">
    <div class="container">
        <div class="row g-4">
              <div class="site-heading text-center">
                            <span class="site-title-tagline">Our Blog</span>
                            <h2 class="site-title">News & Blog</h2>
                            <div class="heading-divider"></div>
                            <p>
                                It is a long established fact that a reader will be distracted by the readable content
                                of a page when looking at its layout.
                            </p>
                        </div>

            @forelse($services as $service)
                <div class="col-md-6 col-lg-3">
                    <div class="service-item">

                        {{-- Image --}}
                        <div class="service-icon">
                            <img 
                                src="{{ $service->getFirstMediaUrl('service_image') ?: asset('assets/img/icon/icon-20.svg') }}" 
                                alt="{{ $service->title }}"
                            >
                        </div>

                        {{-- Content --}}
                        <div class="service-content">
                            <h3 class="service-title">
                                <a href="#">{{ $service->title }}</a>
                            </h3>

                            <p class="service-text">
                                {{ Str::limit($service->description, 80) }}
                            </p>

                            <div class="service-arrow">
                                <a href="{{ route('services.show', $service->id) }}" class="theme-btn">
                                    Read More <i class="far fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center">
                    <h4>No Services Found</h4>
                </div>
            @endforelse

        </div>
    </div>
</div>



<!-- contact area -->
<div class="contact-area py-120">
    <div class="container">
        <div class="contact-wrapper">
            <div class="row">

                {{-- 🔵 LEFT FORM --}}
                <div class="col-lg-8 align-self-center">
                    <div class="contact-form">

                        <div class="contact-form-header">
                            <h2>Get In Touch</h2>
                            <p>Feel free to contact us anytime. We will get back to you as soon as possible.</p>
                        </div>

                        {{-- SUCCESS MESSAGE --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- ERROR MESSAGE --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- FORM --}}
                        <form method="POST" action="{{ route('contact.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="name"
                                               class="form-control"
                                               placeholder="Your Name"
                                               value="{{ old('name') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" name="email"
                                               class="form-control"
                                               placeholder="Your Email"
                                               value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" name="phone"
                                       class="form-control"
                                       placeholder="Your Phone"
                                       value="{{ old('phone') }}">
                            </div>

                            <div class="form-group">
                                <textarea name="message"
                                          class="form-control"
                                          rows="5"
                                          placeholder="Write Your Message"
                                          required>{{ old('message') }}</textarea>
                            </div>

                            <button type="submit" class="theme-btn">
                                <i class="far fa-paper-plane"></i> Send Message
                            </button>

                        </form>
                    </div>
                </div>

                {{-- 🟢 RIGHT CONTACT INFO --}}
                <div class="col-lg-4">
                    <div class="contact-content">

                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Address</h5>
                                <p>{{ $setting->address ?? 'No address available' }}</p>
                            </div>
                        </div>

                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Call Us</h5>
                                <p>{{ $setting->phone ?? 'No phone' }}</p>
                            </div>
                        </div>

                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Email</h5>
                                <p>{{ $setting->email ?? 'No email' }}</p>
                            </div>
                        </div>

                        {{-- Social Links --}}
                        <div class="contact-info">
                            <div class="contact-info-icon">
                                <i class="fal fa-share-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Follow Us</h5>

                               <div class="social-icons mt-3">

    @if($setting->facebook)
        <a href="{{ $setting->facebook }}" target="_blank" class="social-icon fb">
            <i class="fab fa-facebook-f"></i>
        </a>
    @endif

    @if($setting->instagram)
        <a href="{{ $setting->instagram }}" target="_blank" class="social-icon ig">
            <i class="fab fa-instagram"></i>
        </a>
    @endif

    @if($setting->twitter)
        <a href="{{ $setting->twitter }}" target="_blank" class="social-icon tw">
            <i class="fab fa-twitter"></i>
        </a>
    @endif

    @if($setting->youtube)
        <a href="{{ $setting->youtube }}" target="_blank" class="social-icon yt">
            <i class="fab fa-youtube"></i>
        </a>
    @endif

</div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- map -->
<div class="contact-map">
    <iframe 
        src="https://maps.google.com/maps?q={{ urlencode($setting->address ?? 'India') }}&output=embed"
        style="border:0; width:100%; height:400px;" 
        allowfullscreen="" loading="lazy">
    </iframe>
</div>



@endsection