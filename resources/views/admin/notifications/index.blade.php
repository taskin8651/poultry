@extends('layouts.admin')

@section('content')

@php
    $styles = [
        'success' => ['icon' => 'fa-circle-check', 'text' => 'text-emerald-500', 'badge' => 'badge-success'],
        'error'   => ['icon' => 'fa-circle-exclamation', 'text' => 'text-red-500', 'badge' => 'badge-danger'],
        'warning' => ['icon' => 'fa-triangle-exclamation', 'text' => 'text-amber-500', 'badge' => 'bg-amber-100 text-amber-700'],
        'info'    => ['icon' => 'fa-circle-info', 'text' => 'text-indigo-500', 'badge' => 'badge-info'],
    ];
@endphp

<div class="mx-auto w-full">

    {{-- HEADER --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">
            <div class="page-icon">
                <i class="fas fa-bell"></i>
            </div>

            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Notifications
                    </h1>
                    <span class="badge-premium badge-info">
                        {{ $notifications->total() }} Total
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    Orders, referrals, offers and messages that need your attention.
                </p>
            </div>
        </div>

        @if($notifications->contains(fn($n) => is_null($n->read_at)))
            <form method="POST" action="{{ route('notifications.readAll') }}">
                @csrf
                <button type="submit" class="btn-premium btn-premium-outline">
                    <i class="fas fa-check-double"></i> Mark all as read
                </button>
            </form>
        @endif
    </div>

    {{-- LIST --}}
    <div class="card-premium overflow-hidden">

        @forelse($notifications as $n)
            @php $s = $styles[$n->data['type'] ?? 'info'] ?? $styles['info']; @endphp

            <form method="POST" action="{{ route('notifications.read', $n->id) }}"
                  class="{{ $loop->last ? '' : 'border-b border-slate-100' }}">
                @csrf
                <input type="hidden" name="redirect" value="{{ $n->data['url'] ?? route('notifications.page') }}">

                <button type="submit"
                        class="w-full flex items-start gap-4 px-6 py-4 text-left hover:bg-slate-50 transition
                               {{ $n->read_at ? '' : 'bg-indigo-50/50' }}">

                    <i class="fas {{ $s['icon'] }} {{ $s['text'] }} mt-0.5"></i>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800">{{ $n->data['title'] ?? '' }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $n->data['message'] ?? '' }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $n->created_at->diffForHumans() }}</p>
                    </div>

                    @unless($n->read_at)
                        <span class="badge-premium {{ $s['badge'] }}">New</span>
                    @endunless
                </button>
            </form>
        @empty
            <div class="py-14 text-center text-sm text-slate-400">
                <i class="fas fa-bell-slash text-2xl mb-3 block"></i>
                No notifications yet.
            </div>
        @endforelse

    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>

</div>

@endsection
