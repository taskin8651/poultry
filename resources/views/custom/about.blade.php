@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url('{{ asset('assets/img/breadcrumb/breadcrumb.jpg') }}')">
    <div class="container">
        <h2 class="breadcrumb-title">About Us</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">About Us</li>
        </ul>
    </div>
</div>

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

  <!-- counter area -->
        <div class="counter-area pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/icon-15.svg" alt="">
                            </div>
                            <div class="counter-content">
                                <span><span class="counter" data-count="+" data-to="1500" data-speed="3000">1500</span><span class="counter-plus">+</span></span>
                                <h6 class="title">Satisfied Customer</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/icon-1.svg" alt="">
                            </div>
                            <div class="counter-content">
                                <span class="counter" data-count="+" data-to="50" data-speed="3000">50</span><span class="counter-plus">k+</span>
                                <h6 class="title">Total Poultry</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/icon-11.svg" alt="">
                            </div>
                            <div class="counter-content">
                                <span class="counter" data-count="+" data-to="120" data-speed="3000">120</span><span class="counter-plus">+</span>
                                <h6 class="title">Experts Staff</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="counter-box">
                            <div class="icon">
                                <img src="assets/img/icon/icon-13.svg" alt="">
                            </div>
                            <div class="counter-content">
                                <span class="counter" data-count="+" data-to="50" data-speed="3000">50</span><span class="counter-plus">+</span>
                                <h6 class="title">Win Awards</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- counter area end -->

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



@endsection