@extends('custom.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/user-panel.css') }}">
@endpush

@section('content')

<div class="user-panel">
    <div class="container">

        {{-- TOP BAR --}}
        <div class="user-topbar">
            <div class="user-topbar-left">
                @if(auth()->user()->profile_image_url)
                    <img src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}"
                         class="user-topbar-avatar" style="object-fit:cover;">
                @else
                    <div class="user-topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                @endif
                <div>
                    <h1 class="user-topbar-title">@yield('account-title', 'My Account')</h1>
                    <p class="user-topbar-sub">@yield('account-subtitle', 'Manage your orders, profile and account preferences.')</p>
                </div>
            </div>
            <a href="{{ url('/') }}" class="user-btn user-btn-outline">
                <i class="far fa-arrow-left"></i> Back to Site
            </a>
        </div>

        <div class="row g-4">

            {{-- SIDEBAR --}}
            <div class="col-lg-3">
                <div class="user-sidebar-card">

                    <div class="user-sidebar-head">
                        <div class="user-avatar-ring">
                            @if(auth()->user()->profile_image_url)
                                <img src="{{ auth()->user()->profile_image_url }}" alt="{{ auth()->user()->name }}"
                                     class="user-avatar" style="object-fit:cover;">
                            @else
                                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            @endif
                        </div>
                        <h6 class="user-sidebar-name">{{ auth()->user()->name }}</h6>
                        <span class="user-sidebar-email">{{ auth()->user()->email }}</span>
                        <span class="user-member-chip">
                            <i class="far fa-star"></i> Member since {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                    </div>

                    <nav class="user-nav">
                        <a href="{{ route('account.dashboard') }}" class="user-nav-link {{ request()->routeIs('account.dashboard') ? 'active' : '' }}">
                            <span class="user-nav-icon"><i class="far fa-th-large"></i></span> Dashboard
                        </a>
                        <a href="{{ route('orders.index') }}" class="user-nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <span class="user-nav-icon"><i class="far fa-box"></i></span> My Orders
                        </a>
                        <a href="{{ route('referrals.index') }}" class="user-nav-link {{ request()->routeIs('referrals.index') ? 'active' : '' }}">
                            <span class="user-nav-icon"><i class="far fa-gift"></i></span> Refer &amp; Earn
                        </a>
                        <a href="{{ route('account.profile.edit') }}" class="user-nav-link {{ request()->routeIs('account.profile.edit') ? 'active' : '' }}">
                            <span class="user-nav-icon"><i class="far fa-user"></i></span> Edit Profile
                        </a>
                        <a href="{{ route('account.password.edit') }}" class="user-nav-link {{ request()->routeIs('account.password.edit') ? 'active' : '' }}">
                            <span class="user-nav-icon"><i class="far fa-lock"></i></span> Change Password
                        </a>
                        <a href="{{ route('cart.index') }}" class="user-nav-link">
                            <span class="user-nav-icon"><i class="far fa-shopping-cart"></i></span> My Cart
                        </a>
                        <a href="#" class="user-nav-link user-nav-link-danger"
                           onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();">
                            <span class="user-nav-icon"><i class="far fa-sign-out"></i></span> Logout
                        </a>
                    </nav>

                </div>

                <form id="account-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>

            {{-- CONTENT --}}
            <div class="col-lg-9">

                @yield('account-content')

            </div>

        </div>
    </div>
</div>

@endsection
