@extends('user.layout')

@section('account-title', 'Notifications')
@section('account-subtitle', 'Everything that needs your attention, in one place.')

@section('account-content')

@php
    $iconMap  = ['success' => 'fa-check-circle', 'error' => 'fa-exclamation-circle', 'warning' => 'fa-exclamation-triangle', 'info' => 'fa-info-circle'];
    $colorMap = ['success' => 'var(--up-success)', 'error' => 'var(--up-danger)', 'warning' => 'var(--up-warning)', 'info' => 'var(--up-info)'];
@endphp

<div class="user-card">

    <div class="user-card-header d-flex justify-content-between align-items-center">
        <span>All Notifications</span>

        @if($notifications->contains(fn($n) => is_null($n->read_at)))
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="user-btn user-btn-sm user-btn-outline">
                    <i class="far fa-check-double"></i> Mark all as read
                </button>
            </form>
        @endif
    </div>

    <div class="user-card-body p-0">

        @forelse($notifications as $n)
            @php $type = $n->data['type'] ?? 'info'; @endphp

            <form method="POST" action="{{ route('notifications.read', $n->id) }}"
                  style="border-bottom:1px solid var(--up-border);">
                @csrf
                <input type="hidden" name="redirect" value="{{ $n->data['url'] ?? route('notifications.page') }}">

                <button type="submit"
                        class="d-flex gap-3 align-items-start w-100 text-start px-4 py-3 border-0"
                        style="background:{{ $n->read_at ? 'transparent' : 'var(--up-primary-soft)' }};">

                    <i class="far {{ $iconMap[$type] ?? $iconMap['info'] }} mt-1"
                       style="color:{{ $colorMap[$type] ?? $colorMap['info'] }};font-size:18px;"></i>

                    <div class="flex-grow-1">
                        <div class="fw-bold" style="color:var(--up-text);">{{ $n->data['title'] ?? '' }}</div>
                        <div class="small" style="color:var(--up-muted);">{{ $n->data['message'] ?? '' }}</div>
                        <div class="small mt-1" style="color:var(--up-muted);">{{ $n->created_at->diffForHumans() }}</div>
                    </div>

                    @unless($n->read_at)
                        <span class="user-badge user-badge-confirmed">New</span>
                    @endunless
                </button>
            </form>
        @empty
            <div class="user-empty">
                <i class="far fa-bell-slash"></i>
                <h5 class="mb-1" style="color:var(--up-text);">No Notifications</h5>
                <p class="mb-0">You're all caught up.</p>
            </div>
        @endforelse

    </div>
</div>

<div class="mt-4">
    {{ $notifications->links() }}
</div>

@endsection
