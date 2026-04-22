@php
    $setting = \App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from live.themewild.com/poultryfarm/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Apr 2026 05:48:16 GMT -->
<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $setting->description ?? 'Default description goes here.' }}">
    <meta name="keywords" content="{{ $setting->keywords ?? 'Default keywords go here.' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- title -->
    <title>{{ $setting->title ?? 'Poultryfarm - Poultry Farm HTML5 Template' }}</title>

   <!-- favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}">

<!-- css -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/all-fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

</head>

<body>

    <!-- preloader -->
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader end -->


    <!-- header area -->
    <header class="header">

        <!-- top header -->
        <div class="header-top">
            <div class="container">
                <div class="header-top-wrapper">
                    <div class="header-top-left">
                        <div class="header-top-contact">
                            <ul>
                                <li><a href="#"><i class="far fa-map-marker-alt"></i>{{ $setting->address ?? 'Default Address' }}</a></li>
                                <li><a href="tel:{{ $setting->phone ?? 'Default Phone' }}"><i class="far fa-phone"></i>{{ $setting->phone ?? 'Default Phone' }}</a></li>
                                <li><a href="mailto:{{ $setting->email ?? 'default@example.com' }}"><i
                                            class="far fa-envelope"></i>{{ $setting->email ?? 'Default Email' }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="header-top-right">
                        <div class="header-top-social">
                            <span>Follow Us:</span>
                            <a href="{{ $setting->facebook ?? '#' }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            <a href="{{ $setting->twitter ?? '#' }}" target="_blank"><i class="fab fa-x-twitter"></i></a>
                            <a href="{{ $setting->instagram ?? '#' }}" target="_blank"><i class="fab fa-instagram"></i></a>
                            <a href="{{ $setting->youtube ?? '#' }}" target="_blank"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-navigation">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ $setting->logo ?? asset('assets/img/logo/logo.png') }}" alt="logo">
                    </a>
                    <div class="mobile-menu-right">
                      <div class="header-nav-icons d-lg-none d-lg-flex align-items-center gap-3">

    {{-- 🛒 Cart --}}
    @php
    $cartCount = count(session('cart', []));
@endphp

<a href="{{ url('/cart') }}" 
   class="nav-icon {{ request()->is('cart') ? 'active-icon' : '' }}">

    <i class="far fa-shopping-cart"></i>

    @if($cartCount > 0)
        <span class="badge">{{ $cartCount }}</span>
    @endif

</a>

    @auth
        {{-- 📦 Orders --}}
        @php
            $orderCount = \App\Models\Order::where('user_id', auth()->id())->count();
        @endphp

        <a href="{{ route('orders.index') }}" 
           class="nav-icon {{ request()->is('my-orders*') ? 'active-icon' : '' }}">

            <i class="far fa-box"></i>

            @if($orderCount > 0)
                <span class="badge">{{ $orderCount }}</span>
            @endif
        </a>

        {{-- 👤 Logout --}}
        <a href="#" class="nav-icon"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="far fa-user"></i>
        </a>
    @endauth

    @guest
        <a href="{{ route('login') }}" class="nav-icon">
            <i class="far fa-user"></i>
        </a>
    @endguest

</div>

     <style>
       .nav-icon {
    position: relative;
    font-size: 18px;
    color: #333;
    margin-left: 10px;
    transition: 0.3s;
}

.nav-icon:hover {
    color: #EE7D21;
}

.nav-icon .badge {
    position: absolute;
    top: -5px;
    right: -6px;
    background: #EE7D21;
    color: #fff;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 50%;
}
    </style>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="far fa-stream"></i>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse" id="main_nav">
                       <ul class="navbar-nav">

    <li class="nav-item">
        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
            Home
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">
            About
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('shop*') ? 'active' : '' }}" href="{{ url('/shop') }}">
            Shop
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('services*') ? 'active' : '' }}" href="{{ url('/services') }}">
            Services
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ request()->is('offers') ? 'active' : '' }}" href="{{ url('/offers') }}">
            Offers
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">
            Contact
        </a>
    </li>

</ul>
                        <div class="header-nav-right">
                           
                           <div class="header-nav-cart d-flex align-items-center ">

    {{-- 🛒 Cart --}}
    @php
        $cart = session('cart', []);
        $cartCount = collect($cart)->sum('quantity');
    @endphp

    <a href="{{ url('/cart') }}"
       class="nav-icon {{ request()->is('cart') ? 'active-icon' : '' }}">

        <i class="far fa-shopping-cart"></i>

        @if($cartCount > 0)
            <span class="badge">{{ $cartCount }}</span>
        @endif

    </a>

    @auth
        {{-- 📦 Orders --}}
        @php
            $orderCount = \App\Models\Order::where('user_id', auth()->id())->count();
        @endphp

        <a href="{{ route('orders.index') }}"
           class="nav-icon {{ request()->is('my-orders*') ? 'active-icon' : '' }}"
           title="My Orders">

            <i class="far fa-box"></i>

            @if($orderCount > 0)
                <span class="badge">{{ $orderCount }}</span>
            @endif
             @endauth

        </a>

        <style>
           /* container spacing */
.header-nav-cart {
    margin-left: 15px;
    display: flex;
    align-items: center;
    gap: 14px;
}

/* icon box (modern circle style) */
.nav-icon {
    position: relative;
    width: 42px;
    height: 42px;
    background: #f8f8f8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    font-size: 18px;
    transition: all 0.3s ease;
}

/* hover effect */
.nav-icon:hover {
    background: #EE7D21;
    color: #fff;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(238, 125, 33, 0.3);
}

/* active state */
.nav-icon.active-icon {
    background: #EE7D21;
    color: #fff;
}

/* badge */
.nav-icon .badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #EE7D21;
    color: #fff;
    font-size: 10px;
    padding: 3px 6px;
    border-radius: 50%;
    font-weight: 600;
    min-width: 18px;
    text-align: center;
    line-height: 1;
}

/* badge pulse animation */
.nav-icon .badge {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.15); }
    100% { transform: scale(1); }
}
        </style>

</div>
                            <div class="header-btn">

    @guest
        {{-- 🔐 Login --}}
        <a href="{{ route('login') }}" class="theme-btn">
            Login <i class="far fa-sign-in"></i>
        </a>
    @endguest

    @auth
        {{-- 🚪 Logout --}}
        <a href="#" class="theme-btn"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout <i class="far fa-sign-out"></i>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endauth

</div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- header area end -->


    <!-- popup search -->
    <div class="search-popup">
        <button class="close-search"><span class="far fa-times"></span></button>
        <form action="#">
            <div class="form-group">
                <input type="search" name="search-field" placeholder="Search Here..." required>
                <button type="submit"><i class="far fa-search"></i></button>
            </div>
        </form>
    </div>
    <!-- popup search end -->
    <main class="main">



@yield('content')



</main>
    <!-- footer area -->
    <footer class="footer-area">
        <div class="footer-widget">
            <div class="container">
                <div class="row footer-widget-wrapper pt-100 pb-70">
                    <div class="col-md-6 col-lg-4">
                        <div class="footer-widget-box about-us">
                            <a href="{{ url('/') }}" class="footer-logo">
                                <img src="{{ $setting->logo ?? 'assets/img/logo/logo-light.png' }}" alt="">
                            </a>
                            <p class="mb-20">
                               {{ $setting->footer_text ?? 'Default description about the company or website goes here. You can replace this with actual content.' }}
                            </p>
                            <ul class="footer-social">
                                <li><a href="{{ $setting->facebook ?? '#' }}"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="{{ $setting->instagram ?? '#' }}"><i class="fab fa-instagram"></i></a></li>
                                <li><a href="{{ $setting->twitter ?? '#' }}"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="{{ $setting->youtube ?? '#' }}"><i class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Quick Links</h4>
                            <ul class="footer-list">
                                <li><a href="{{ route('about') }}"><i class="fas fa-caret-right"></i> About Us</a></li>
                                <li><a href="{{ route('services') }}"><i class="fas fa-caret-right"></i> Services</a></li>
                                <li><a href="{{ route('offers') }}"><i class="fas fa-caret-right"></i> Offers</a></li>
                                <li><a href="{{ route('contact') }}"><i class="fas fa-caret-right"></i> Contact Us</a></li>

                                
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box">
                            <h4 class="footer-widget-title">Contact Us</h4>
                            <ul class="footer-contact">
                                <li><i class="far fa-map-marker-alt"></i> {{ $setting->address ?? 'Default address goes here.' }}</li>
                                <li><a href="tel:{{ $setting->phone ?? '+21236547898' }}"><i class="far fa-phone"></i>{{ $setting->phone ?? '+2 123 654 7898' }}</a></li>
                                <li><a href="mailto:{{ $setting->email ?? 'info@example.com' }}"><i
                                            class="far fa-envelope"></i>{{ $setting->email ?? 'info@example.com' }}</a>
                                </li>
                               
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="footer-widget-box list">
                            <h4 class="footer-widget-title">Newsletter</h4>
                            <div class="footer-newsletter">
                                <p>Subscribe Our Newsletter To Get Latest Update And News</p>
                                <div class="subscribe-form">
                                    <form action="#">
                                        <input type="email" class="form-control" placeholder="Your Email">
                                        <button class="theme-btn" type="submit">
                                            <span class="far fa-envelope"></span> Subscribe Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <p class="copyright-text">
                            &copy; Copyright <span id="date"></span> <a href="#"> POULTRYFARM </a> All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-lg-6 align-self-center">
                        <ul class="footer-menu">
                            <li><a href="#">Support</a></li>
                            <li><a href="#">Terms Of Services</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer area end -->




    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="far fa-angle-double-up"></i></a>
    <!-- scroll-top end -->


    <!-- js -->
   <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.appear.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.easing.min.js') }}"></script>
<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/counter-up.js') }}"></script>
<script src="{{ asset('assets/js/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/js/wow.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

</body>


<!-- Mirrored from live.themewild.com/poultryfarm/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 20 Apr 2026 05:49:53 GMT -->
</html>