@extends('custom.master')

@section('content')

<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/breadcrumb.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">@yield('account-title', 'My Account')</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">@yield('account-title', 'My Account')</li>
        </ul>
    </div>
</div>

<div class="container py-120">
    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm mb-4 user-profile-card">
                <div class="p-4 text-center text-white user-profile-card-head">
                    <div class="mx-auto mb-2 d-flex align-items-center justify-content-center fw-bold user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <h6 class="mb-0 text-white">{{ auth()->user()->name }}</h6>
                    <small class="opacity-75">{{ auth()->user()->email }}</small>
                    <div class="small opacity-75 mt-1">
                        <i class="far fa-calendar-alt"></i> Member since {{ auth()->user()->created_at->format('M Y') }}
                    </div>
                </div>

                <div class="list-group list-group-flush">
                    <a href="{{ route('account.dashboard') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ request()->routeIs('account.dashboard') ? 'active-account-link' : '' }}">
                        <i class="far fa-th-large"></i> Dashboard
                    </a>
                    <a href="{{ route('orders.index') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ request()->routeIs('orders.*') ? 'active-account-link' : '' }}">
                        <i class="far fa-box"></i> My Orders
                    </a>
                    <a href="{{ route('referrals.index') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ request()->routeIs('referrals.index') ? 'active-account-link' : '' }}">
                        <i class="far fa-gift"></i> Refer &amp; Earn
                    </a>
                    <a href="{{ route('account.profile.edit') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ request()->routeIs('account.profile.edit') ? 'active-account-link' : '' }}">
                        <i class="far fa-user"></i> Edit Profile
                    </a>
                    <a href="{{ route('account.password.edit') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-2 {{ request()->routeIs('account.password.edit') ? 'active-account-link' : '' }}">
                        <i class="far fa-lock"></i> Change Password
                    </a>
                    <a href="{{ route('cart.index') }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-2">
                        <i class="far fa-shopping-cart"></i> My Cart
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center gap-2 text-danger"
                       onclick="event.preventDefault(); document.getElementById('account-logout-form').submit();">
                        <i class="far fa-sign-out"></i> Logout
                    </a>
                </div>
            </div>

            <form id="account-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

        {{-- CONTENT --}}
        <div class="col-lg-9">

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
                    <i class="far fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
                    <i class="far fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('account-content')

        </div>

    </div>
</div>

<style>
    .user-profile-card{
        border-radius:14px;
        overflow:hidden;
    }
    .user-profile-card-head{
        background:linear-gradient(135deg,#EE7D21,#f7a24d);
    }
    .user-avatar{
        width:64px;
        height:64px;
        border-radius:50%;
        background:rgba(255,255,255,.25);
        font-size:24px;
    }
    .list-group-item-action {
        border: none;
        padding: 14px 20px;
        color: #444;
        font-weight: 500;
        transition: 0.2s;
    }
    .list-group-item-action:hover {
        background: #fff6ec;
        color: #EE7D21;
    }
    .list-group-item-action.active-account-link {
        background: #fff6ec;
        color: #EE7D21;
        border-left: 3px solid #EE7D21;
        font-weight: 700;
    }
    .user-stat-card{
        border-radius:12px;
        transition: 0.25s;
    }
    .user-stat-card:hover{
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,.08) !important;
    }
</style>

@endsection
