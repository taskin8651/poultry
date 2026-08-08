@extends('layouts.admin')

@section('page-title', 'Referrals')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-8 flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-gift-fill"></i>
        </div>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                Referral Program
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Track who's inviting customers and how much has been rewarded.
            </p>
        </div>
        <a href="{{ route('admin.settings.index') }}" class="ml-auto btn-premium btn-premium-outline">
            <i class="bi bi-gear"></i>
            Reward Settings
        </a>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <div class="card-premium p-6">
            <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="bi bi-people-fill"></i>
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Total Referrals</p>
        </div>

        <div class="card-premium p-6">
            <div class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ $stats['rewarded'] }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Rewarded</p>
        </div>

        <div class="card-premium p-6">
            <div class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">{{ $stats['pending'] }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Awaiting First Order</p>
        </div>

        <div class="card-premium p-6">
            <div class="h-12 w-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-xl">
                <i class="bi bi-currency-rupee"></i>
            </div>
            <p class="mt-5 text-3xl font-extrabold text-slate-900">₹{{ number_format($stats['payout'], 0) }}</p>
            <p class="mt-1 text-sm font-semibold text-slate-400">Total Paid Out</p>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card-premium overflow-hidden">
        <div class="table-premium-wrap">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Referrer</th>
                        <th>Referred User</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Reward</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                        <tr>
                            <td>
                                <p class="font-bold text-slate-800">{{ $referral->referrer->name ?? '—' }}</p>
                                <p class="text-xs text-slate-400">{{ $referral->referrer->email ?? '' }}</p>
                            </td>
                            <td>
                                <p class="font-bold text-slate-800">{{ $referral->referred->name ?? '—' }}</p>
                                <p class="text-xs text-slate-400">{{ $referral->referred->email ?? '' }}</p>
                            </td>
                            <td>
                                @if($referral->order)
                                    <a href="{{ route('admin.orders.show', $referral->order_id) }}" class="font-semibold text-indigo-600 hover:text-indigo-700">
                                        #{{ $referral->order_id }}
                                    </a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td>
                                @if($referral->status === 'rewarded')
                                    <span class="badge-premium badge-success">Rewarded</span>
                                @else
                                    <span class="badge-premium badge-neutral">Pending</span>
                                @endif
                            </td>
                            <td class="font-bold text-slate-800">
                                {{ $referral->reward_amount ? '₹' . number_format($referral->reward_amount, 2) : '—' }}
                            </td>
                            <td class="text-slate-500">
                                {{ $referral->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 py-12">
                                No referrals yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($referrals->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $referrals->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
