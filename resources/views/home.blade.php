@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')

@php
    $statusBadge = [
        'pending'   => 'badge-neutral',
        'confirmed' => 'badge-info',
        'delivered' => 'badge-success',
        'cancelled' => 'badge-danger',
    ];
@endphp

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <div class="page-icon">
                <i class="bi bi-speedometer2"></i>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                    Welcome back, {{ explode(' ', auth()->user()->name)[0] }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Here's what's happening with your poultry business today.
                </p>
            </div>
        </div>
        <div class="text-sm font-semibold text-slate-400">
            {{ now()->format('l, d M Y') }}
        </div>
    </div>

    @if(session('status'))
        <div class="alert-premium-success mb-6">
            <i class="fas fa-check-circle"></i>
            {{ session('status') }}
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <div class="card-premium p-6">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span class="badge-premium badge-info">Total</span>
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ $stats['users'] }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Registered Users</p>
        </div>

        <div class="card-premium p-6">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                @if($stats['low_stock'] > 0)
                    <span class="badge-premium badge-danger">{{ $stats['low_stock'] }} low stock</span>
                @else
                    <span class="badge-premium badge-success">Healthy</span>
                @endif
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ $stats['products'] }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Products</p>
        </div>

        <div class="card-premium p-6">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <span class="badge-premium badge-neutral">All time</span>
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ $stats['orders'] }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Orders Placed</p>
        </div>

        <div class="card-premium p-6">
            <div class="flex items-center justify-between">
                <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                    <i class="bi bi-currency-rupee"></i>
                </div>
                <span class="badge-premium badge-success">Revenue</span>
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">₹{{ number_format($stats['revenue'], 0) }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Total Sales</p>
        </div>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- RECENT ORDERS --}}
        <div class="xl:col-span-2 card-premium overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-slate-100">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">Recent Orders</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Latest activity across your store</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn-premium btn-premium-outline !py-2 !px-4">
                    View All <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="table-premium-wrap">
                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="font-bold text-slate-800">#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="font-semibold">₹{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge-premium {{ $statusBadge[$order->status] ?? 'badge-neutral' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-slate-400 py-10">
                                    No orders yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RECENT CONTACTS + QUICK LINKS --}}
        <div class="space-y-6">

            <div class="card-premium overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-extrabold text-slate-900">Recent Messages</h2>
                    <p class="text-sm text-slate-500 mt-0.5">From your contact form</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentContacts as $contact)
                        <div class="p-4 flex items-start gap-3">
                            <div class="h-9 w-9 shrink-0 rounded-full bg-gradient-to-br from-indigo-600 to-violet-600 text-white flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $contact->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $contact->message }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="p-6 text-center text-sm text-slate-400">No messages yet.</p>
                    @endforelse
                </div>
                @can('contact_access')
                    <div class="p-4 border-t border-slate-100">
                        <a href="{{ route('admin.contacts.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700">
                            View all messages <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @endcan
            </div>

            <div class="card-premium p-6">
                <h2 class="text-lg font-extrabold text-slate-900 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 gap-3">
                    @can('product_access')
                        <a href="{{ route('admin.products.create') }}" class="btn-premium btn-premium-outline justify-start">
                            <i class="bi bi-plus-circle text-indigo-600"></i> Product
                        </a>
                    @endcan
                    @can('offer_access')
                        <a href="{{ route('admin.offers.create') }}" class="btn-premium btn-premium-outline justify-start">
                            <i class="bi bi-gift text-violet-600"></i> Offer
                        </a>
                    @endcan
                    @can('user_create')
                        <a href="{{ route('admin.users.create') }}" class="btn-premium btn-premium-outline justify-start">
                            <i class="bi bi-person-plus text-sky-600"></i> User
                        </a>
                    @endcan
                    @can('setting_access')
                        <a href="{{ route('admin.settings.index') }}" class="btn-premium btn-premium-outline justify-start">
                            <i class="bi bi-gear text-slate-600"></i> Settings
                        </a>
                    @endcan
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')
@parent
@endsection
