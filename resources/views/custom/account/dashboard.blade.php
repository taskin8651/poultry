@extends('custom.account.layout')

@section('account-title', 'Dashboard')

@section('account-content')

@php
    $statusBadge = [
        'pending'   => 'bg-secondary',
        'confirmed' => 'bg-info text-dark',
        'delivered' => 'bg-success',
        'cancelled' => 'bg-danger',
    ];
@endphp

<h4 class="mb-1">Welcome back, {{ explode(' ', $user->name)[0] }} 👋</h4>
<p class="text-muted mb-4">Here's a quick look at your account.</p>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius:12px;">
            <i class="far fa-box fa-lg mb-2" style="color:#EE7D21;"></i>
            <h4 class="mb-0">{{ $stats['total_orders'] }}</h4>
            <small class="text-muted">Total Orders</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius:12px;">
            <i class="far fa-hourglass-half fa-lg mb-2 text-warning"></i>
            <h4 class="mb-0">{{ $stats['pending_orders'] }}</h4>
            <small class="text-muted">Pending Orders</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius:12px;">
            <i class="far fa-wallet fa-lg mb-2 text-success"></i>
            <h4 class="mb-0">₹ {{ number_format($stats['wallet_balance'], 2) }}</h4>
            <small class="text-muted">Wallet Balance</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius:12px;">
            <i class="far fa-users fa-lg mb-2 text-primary"></i>
            <h4 class="mb-0">{{ $stats['referrals'] }}</h4>
            <small class="text-muted">People Invited</small>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- RECENT ORDERS --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold">Recent Orders</span>
                <a href="{{ route('orders.index') }}" class="small" style="color:#EE7D21;">View All <i class="far fa-arrow-right"></i></a>
            </div>
            <div class="card-body p-0">
                @forelse($recentOrders as $order)
                    <div class="d-flex justify-content-between align-items-center p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <a href="{{ route('orders.show', $order->id) }}" class="fw-bold text-dark text-decoration-none">
                                Order #{{ $order->id }}
                            </a>
                            <div class="small text-muted">{{ $order->created_at->format('d M Y, h:i A') }} &middot; {{ $order->total_qty }} items</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">₹ {{ number_format($order->total_amount, 2) }}</div>
                            <span class="badge {{ $statusBadge[$order->status] ?? 'bg-secondary' }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="far fa-box-open fa-2x mb-2 d-block"></i>
                        You haven't placed any orders yet.
                        <div class="mt-3">
                            <a href="{{ route('shop') }}" class="theme-btn btn-sm">Start Shopping</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS + PROFILE SNAPSHOT --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
            <div class="card-header bg-white fw-bold">Your Details</div>
            <div class="card-body">
                <p class="mb-2"><i class="far fa-envelope me-2 text-muted"></i>{{ $user->email }}</p>
                <p class="mb-2"><i class="far fa-phone me-2 text-muted"></i>{{ $user->phone ?: 'Not set' }}</p>
                <p class="mb-0"><i class="far fa-map-marker-alt me-2 text-muted"></i>{{ $user->address ?: 'Not set' }}</p>
                <a href="{{ route('account.profile.edit') }}" class="theme-btn btn-sm mt-3 w-100 text-center">
                    <i class="far fa-pencil"></i> Edit Profile
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm text-white" style="border-radius:14px;background:linear-gradient(135deg,#EE7D21,#f7a24d);">
            <div class="card-body text-center">
                <i class="far fa-gift fa-2x mb-2"></i>
                <p class="mb-1 opacity-75">Invite friends, earn cashback</p>
                <a href="{{ route('referrals.index') }}" class="theme-btn btn-sm mt-2" style="background:#fff;color:#EE7D21;">
                    Refer &amp; Earn
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
