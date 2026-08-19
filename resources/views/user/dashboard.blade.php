@extends('user.layout')

@section('account-title', 'Dashboard')
@section('account-subtitle')Welcome back, {{ explode(' ', $user->name)[0] }} 👋@endsection

@section('account-content')

@php
    $statusBadge = [
        'pending'   => 'user-badge-pending',
        'confirmed' => 'user-badge-confirmed',
        'delivered' => 'user-badge-delivered',
        'cancelled' => 'user-badge-cancelled',
    ];
@endphp

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <div class="user-stat-icon" style="background:var(--up-primary-soft);color:var(--up-primary);">
                <i class="far fa-box"></i>
            </div>
            <p class="user-stat-value">{{ $stats['total_orders'] }}</p>
            <span class="user-stat-label">Total Orders</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <div class="user-stat-icon" style="background:var(--up-warning-soft);color:var(--up-warning);">
                <i class="far fa-hourglass-half"></i>
            </div>
            <p class="user-stat-value">{{ $stats['pending_orders'] }}</p>
            <span class="user-stat-label">Pending Orders</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <div class="user-stat-icon" style="background:var(--up-success-soft);color:var(--up-success);">
                <i class="far fa-wallet"></i>
            </div>
            <p class="user-stat-value">₹ {{ number_format($stats['wallet_balance'], 2) }}</p>
            <span class="user-stat-label">Wallet Balance</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <div class="user-stat-icon" style="background:var(--up-info-soft);color:var(--up-info);">
                <i class="far fa-users"></i>
            </div>
            <p class="user-stat-value">{{ $stats['referrals'] }}</p>
            <span class="user-stat-label">People Invited</span>
        </div>
    </div>
</div>

<div class="row g-4">

    {{-- RECENT ORDERS --}}
    <div class="col-lg-8">
        <div class="user-card h-100">
            <div class="user-card-header">
                <span>Recent Orders</span>
                <a href="{{ route('orders.index') }}">View All <i class="far fa-arrow-right"></i></a>
            </div>
            <div class="user-card-body-flush">
                @forelse($recentOrders as $order)
                    <div class="d-flex justify-content-between align-items-center p-3 px-4 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:var(--up-border);">
                        <div>
                            <a href="{{ route('orders.show', $order->id) }}" class="fw-bold text-decoration-none" style="color:var(--up-text);">
                                Order #{{ $order->id }}
                            </a>
                            <div class="small" style="color:var(--up-muted);">{{ $order->created_at?->format('d M Y, h:i A') ?? '-' }} &middot; {{ $order->total_qty }} items</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">₹ {{ number_format($order->total_amount, 2) }}</div>
                            <span class="user-badge {{ $statusBadge[$order->status] ?? 'user-badge-pending' }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="user-empty">
                        <i class="far fa-box-open"></i>
                        You haven't placed any orders yet.
                        <div class="mt-3">
                            <a href="{{ route('shop') }}" class="user-btn user-btn-sm">Start Shopping</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS + PROFILE SNAPSHOT --}}
    <div class="col-lg-4">

        <div class="user-card mb-4">
            <div class="user-card-header">Your Details</div>
            <div class="user-card-body">
                <p class="mb-2"><i class="far fa-envelope me-2" style="color:var(--up-muted);"></i>{{ $user->email }}</p>
                <p class="mb-2"><i class="far fa-phone me-2" style="color:var(--up-muted);"></i>{{ $user->phone ?: 'Not set' }}</p>
                <p class="mb-0"><i class="far fa-map-marker-alt me-2" style="color:var(--up-muted);"></i>{{ $user->address ?: 'Not set' }}</p>
                <a href="{{ route('account.profile.edit') }}" class="user-btn user-btn-outline user-btn-sm user-btn-block mt-3">
                    <i class="far fa-pencil"></i> Edit Profile
                </a>
            </div>
        </div>

        <div class="user-card" style="background:linear-gradient(135deg,var(--up-primary),var(--up-primary-dark));border:none;">
            <div class="user-card-body text-center text-white">
                <i class="far fa-gift fa-2x mb-2"></i>
                <p class="mb-1" style="opacity:.85;">Invite friends, earn cashback</p>
                <a href="{{ route('referrals.index') }}" class="user-btn user-btn-sm mt-2" style="background:#fff;color:var(--up-primary-dark);box-shadow:none;">
                    Refer &amp; Earn
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
