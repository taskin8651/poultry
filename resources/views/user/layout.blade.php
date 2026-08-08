@extends('custom.master')

@section('content')

<div class="user-panel">
    <div class="container">

        {{-- TOP BAR --}}
        <div class="user-topbar">
            <div class="user-topbar-left">
                <div class="user-topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
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
                            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
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

                @if(session('success'))
                    <div class="user-alert user-alert-success">
                        <i class="far fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="user-alert user-alert-danger">
                        <i class="far fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="user-alert user-alert-danger">
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
</div>

<style>
/* ==========================================================================
   USER PANEL — premium design system
   ========================================================================== */
.user-panel{
    --up-primary:#EE7D21;
    --up-primary-dark:#C75D0F;
    --up-primary-light:#f7a24d;
    --up-primary-soft:#FFF1E2;
    --up-bg:#F6F7FB;
    --up-surface:#FFFFFF;
    --up-border:rgba(17,24,39,.07);
    --up-text:#1F2430;
    --up-muted:#7C8494;
    --up-success:#16A34A;
    --up-success-soft:#E7F9EE;
    --up-warning:#B7791F;
    --up-warning-soft:#FEF3C7;
    --up-danger:#DC2626;
    --up-danger-soft:#FDE8E8;
    --up-info:#1D4ED8;
    --up-info-soft:#E7EEFE;
    --up-radius:20px;
    --up-radius-sm:14px;
    --up-shadow:0 10px 30px rgba(17,24,39,.06);
    --up-shadow-hover:0 18px 40px rgba(17,24,39,.10);

    background:var(--up-bg);
    padding:55px 0 90px;
    color:var(--up-text);
}

/* ---------- topbar ---------- */
.user-topbar{
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:20px;margin-bottom:32px;
}
.user-topbar-left{display:flex;align-items:center;gap:18px;}
.user-topbar-avatar{
    width:54px;height:54px;border-radius:16px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    background:linear-gradient(135deg,var(--up-primary),var(--up-primary-light));
    color:#fff;font-weight:700;font-size:20px;
    box-shadow:0 10px 22px rgba(238,125,33,.30);
}
.user-topbar-title{
    margin:0;font-size:26px;font-weight:800;color:var(--up-text);letter-spacing:-.3px;
}
.user-topbar-sub{margin:2px 0 0;color:var(--up-muted);font-size:14.5px;}

/* ---------- buttons ---------- */
.user-btn{
    display:inline-flex;align-items:center;gap:8px;white-space:nowrap;
    padding:11px 22px;border-radius:11px;font-weight:600;font-size:14px;
    background:linear-gradient(135deg,var(--up-primary),var(--up-primary-light));
    color:#fff;border:none;text-decoration:none;cursor:pointer;
    box-shadow:0 10px 22px rgba(238,125,33,.28);transition:transform .2s,box-shadow .2s;
}
.user-btn:hover{transform:translateY(-2px);box-shadow:0 14px 28px rgba(238,125,33,.36);color:#fff;}
.user-btn-sm{padding:8px 16px;font-size:13px;border-radius:9px;}
.user-btn-outline{
    background:var(--up-surface);color:var(--up-text);
    border:1.5px solid var(--up-border);box-shadow:none;
}
.user-btn-outline:hover{background:var(--up-primary-soft);color:var(--up-primary-dark);border-color:var(--up-primary-soft);}
.user-btn-block{width:100%;justify-content:center;}
.user-btn-muted{
    background:#F0F1F5;color:var(--up-text);box-shadow:none;
}
.user-btn-muted:hover{background:#E7E9EF;color:var(--up-text);}

/* ---------- sidebar ---------- */
.user-sidebar-card{
    background:var(--up-surface);border-radius:var(--up-radius);
    border:1px solid var(--up-border);box-shadow:var(--up-shadow);
    overflow:hidden;
}
.user-sidebar-head{
    text-align:center;padding:34px 20px 26px;
    background:
        radial-gradient(120% 120% at 0% 0%, rgba(255,255,255,.16), transparent 55%),
        linear-gradient(135deg,var(--up-primary),var(--up-primary-dark));
    color:#fff;
}
.user-avatar-ring{
    width:82px;height:82px;border-radius:50%;margin:0 auto 14px;
    display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.16);border:2.5px solid rgba(255,255,255,.55);
}
.user-avatar{
    width:66px;height:66px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    background:rgba(255,255,255,.22);font-size:26px;font-weight:800;color:#fff;
}
.user-sidebar-name{color:#fff;margin:0 0 2px;font-weight:700;}
.user-sidebar-email{display:block;color:rgba(255,255,255,.8);font-size:12.5px;margin-bottom:12px;}
.user-member-chip{
    display:inline-flex;align-items:center;gap:6px;
    background:rgba(255,255,255,.18);color:#fff;
    padding:5px 12px;border-radius:20px;font-size:11.5px;font-weight:600;
}
.user-member-chip i{font-size:10px;}

.user-nav{padding:14px;}
.user-nav-link{
    display:flex;align-items:center;gap:12px;
    padding:11px 12px;border-radius:12px;margin-bottom:3px;
    color:var(--up-text);font-weight:600;font-size:14.5px;
    text-decoration:none;transition:.18s;
}
.user-nav-icon{
    width:34px;height:34px;border-radius:10px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
    background:var(--up-primary-soft);color:var(--up-primary);font-size:14px;
    transition:.18s;
}
.user-nav-link:hover{background:var(--up-primary-soft);color:var(--up-primary-dark);}
.user-nav-link.active{
    background:linear-gradient(135deg,var(--up-primary),var(--up-primary-light));
    color:#fff;box-shadow:0 10px 20px rgba(238,125,33,.30);
}
.user-nav-link.active .user-nav-icon{background:rgba(255,255,255,.25);color:#fff;}
.user-nav-link-danger{color:#DC2626;}
.user-nav-link-danger .user-nav-icon{background:var(--up-danger-soft);color:#DC2626;}
.user-nav-link-danger:hover{background:var(--up-danger-soft);color:#DC2626;}

/* ---------- alerts ---------- */
.user-alert{
    display:flex;align-items:flex-start;gap:10px;
    padding:14px 18px;border-radius:var(--up-radius-sm);
    font-size:14.5px;font-weight:500;margin-bottom:20px;
}
.user-alert-success{background:var(--up-success-soft);color:#0F5A2E;}
.user-alert-danger{background:var(--up-danger-soft);color:#8A1C1C;}

/* ---------- generic cards ---------- */
.user-card{
    background:var(--up-surface);border-radius:var(--up-radius);
    border:1px solid var(--up-border);box-shadow:var(--up-shadow);
    overflow:hidden;
}
.user-card-header{
    padding:18px 24px;border-bottom:1px solid var(--up-border);
    display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;
    font-weight:700;font-size:15.5px;
}
.user-card-header a{color:var(--up-primary);font-weight:600;font-size:13.5px;text-decoration:none;}
.user-card-header a:hover{color:var(--up-primary-dark);}
.user-card-body{padding:26px;}
.user-card-body-flush{padding:0;}

/* ---------- stat tiles ---------- */
.user-stat-tile{
    background:var(--up-surface);border-radius:var(--up-radius-sm);
    padding:22px 18px;border:1px solid var(--up-border);box-shadow:var(--up-shadow);
    text-align:center;transition:transform .22s, box-shadow .22s;height:100%;
}
.user-stat-tile:hover{transform:translateY(-5px);box-shadow:var(--up-shadow-hover);}
.user-stat-icon{
    width:46px;height:46px;border-radius:13px;margin:0 auto 12px;
    display:flex;align-items:center;justify-content:center;font-size:18px;
}
.user-stat-value{font-size:23px;font-weight:800;margin:0;color:var(--up-text);}
.user-stat-label{font-size:12.5px;color:var(--up-muted);font-weight:600;text-transform:uppercase;letter-spacing:.4px;}

/* ---------- badges ---------- */
.user-badge{
    display:inline-block;padding:5px 13px;border-radius:20px;
    font-size:11.5px;font-weight:700;letter-spacing:.3px;text-transform:uppercase;
}
.user-badge-pending{background:var(--up-warning-soft);color:var(--up-warning);}
.user-badge-confirmed{background:var(--up-info-soft);color:var(--up-info);}
.user-badge-delivered{background:var(--up-success-soft);color:var(--up-success);}
.user-badge-cancelled{background:var(--up-danger-soft);color:var(--up-danger);}

/* ---------- forms ---------- */
.user-field{margin-bottom:20px;}
.user-field label{
    display:block;font-weight:700;font-size:13px;color:var(--up-text);
    margin-bottom:8px;letter-spacing:.2px;
}
.user-field-hint{display:block;margin-top:6px;font-size:12.5px;color:var(--up-muted);}
.user-input-wrap{position:relative;}
.user-input-wrap i{
    position:absolute;left:16px;top:50%;transform:translateY(-50%);
    color:var(--up-muted);font-size:14px;pointer-events:none;
}
.user-input, .user-textarea{
    width:100%;border:1.5px solid var(--up-border);border-radius:12px;
    padding:12.5px 16px 12.5px 44px;font-size:14.5px;background:#FBFBFD;
    transition:.18s;color:var(--up-text);
}
.user-textarea{padding-left:16px;resize:vertical;}
.user-input:focus, .user-textarea:focus{
    outline:none;border-color:var(--up-primary);background:#fff;
    box-shadow:0 0 0 4px rgba(238,125,33,.12);
}
.user-input:disabled{background:#F1F2F6;color:var(--up-muted);}

/* ---------- misc ---------- */
.user-empty{text-align:center;padding:60px 20px;color:var(--up-muted);}
.user-empty i{font-size:38px;margin-bottom:14px;display:block;color:var(--up-border);}
.user-table thead th{
    background:#FAFAFC;color:var(--up-muted);font-size:12px;font-weight:700;
    text-transform:uppercase;letter-spacing:.4px;border-bottom:1px solid var(--up-border);
}
.user-table td, .user-table th{padding:14px 20px;vertical-align:middle;border-color:var(--up-border);}
.user-table tbody tr:hover{background:#FBFBFD;}

@media (max-width:991px){
    .user-topbar-title{font-size:22px;}
    .user-card-body{padding:20px;}
}
</style>

@endsection
