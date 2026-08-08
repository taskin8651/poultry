@extends('user.layout')

@section('account-title', 'Refer & Earn')
@section('account-subtitle', 'Invite friends and earn wallet cashback on their first order.')

@section('account-content')

@php
    $referralLink = url('/register?ref=' . $user->referral_code);
    $shareText = 'Join and shop with me! Use my referral code ' . $user->referral_code . ' when you sign up: ' . $referralLink;
@endphp

<div class="row g-4 mb-4">

    {{-- REFERRAL CODE / SHARE CARD --}}
    <div class="col-lg-8">
        <div class="user-card h-100">
            <div class="user-card-body">
                <h4 class="mb-2 fw-bold"><i class="far fa-gift" style="color:var(--up-warning);"></i> Refer friends, earn wallet cash</h4>
                <p class="mb-4" style="color:var(--up-muted);">
                    Share your referral code with friends. When someone signs up with your code
                    and places their first order, you get rewarded straight into your wallet.
                </p>

                <div class="user-field">
                    <label>Your Referral Code</label>
                    <div class="input-group">
                        <input type="text" class="user-input fw-bold text-uppercase" style="padding-left:16px;" id="referral-code" value="{{ $user->referral_code }}" readonly>
                        <button class="user-btn" type="button" onclick="copyText('referral-code', this)">
                            <i class="far fa-copy"></i> Copy Code
                        </button>
                    </div>
                </div>

                <div class="user-field mb-0">
                    <label>Your Referral Link</label>
                    <div class="input-group">
                        <input type="text" class="user-input" style="padding-left:16px;" id="referral-link" value="{{ $referralLink }}" readonly>
                        <button class="user-btn" type="button" onclick="copyText('referral-link', this)">
                            <i class="far fa-link"></i> Copy Link
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a class="user-btn user-btn-outline" target="_blank"
                       href="https://wa.me/?text={{ urlencode($shareText) }}">
                        <i class="fab fa-whatsapp"></i> Share on WhatsApp
                    </a>
                    <a class="user-btn user-btn-outline" target="_blank"
                       href="mailto:?subject={{ urlencode('Join me and get rewards') }}&body={{ urlencode($shareText) }}">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- WALLET BALANCE CARD --}}
    <div class="col-lg-4">
        <div class="user-card h-100" style="background:linear-gradient(135deg,var(--up-primary),var(--up-primary-dark));border:none;">
            <div class="user-card-body d-flex flex-column justify-content-center text-center text-white h-100">
                <i class="far fa-wallet fa-2x mb-3"></i>
                <p class="mb-1" style="opacity:.85;">Wallet Balance</p>
                <h2 class="fw-bold mb-0">₹ {{ number_format($user->wallet_balance, 2) }}</h2>
                <p class="mt-3 mb-0 small" style="opacity:.85;">
                    Used automatically at checkout when you tick "Use wallet balance".
                </p>
            </div>
        </div>
    </div>

</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <p class="user-stat-value">{{ $referrals->count() }}</p>
            <span class="user-stat-label">Total Invited</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <p class="user-stat-value" style="color:var(--up-success);">{{ $referrals->where('status', 'rewarded')->count() }}</p>
            <span class="user-stat-label">Rewarded</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <p class="user-stat-value" style="color:var(--up-warning);">{{ $referrals->where('status', 'pending')->count() }}</p>
            <span class="user-stat-label">Awaiting Order</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="user-stat-tile">
            <p class="user-stat-value" style="color:var(--up-primary);">₹ {{ number_format($referrals->where('status', 'rewarded')->sum('reward_amount'), 2) }}</p>
            <span class="user-stat-label">Total Earned</span>
        </div>
    </div>
</div>

{{-- REFERRALS LIST --}}
<div class="user-card mb-4">
    <div class="user-card-header">People You've Invited</div>
    <div class="user-card-body-flush">
        <div class="table-responsive">
            <table class="table user-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Reward</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                        <tr>
                            <td class="ps-4">{{ $referral->referred->name ?? 'Deleted User' }}</td>
                            <td>{{ $referral->created_at->format('d M Y') }}</td>
                            <td>
                                @if($referral->status === 'rewarded')
                                    <span class="user-badge user-badge-delivered">Rewarded</span>
                                @else
                                    <span class="user-badge user-badge-pending">Awaiting Order</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end fw-bold">
                                {{ $referral->reward_amount ? '₹ ' . number_format($referral->reward_amount, 2) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="user-empty border-0">
                                You haven't invited anyone yet. Share your code to start earning!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- WALLET HISTORY --}}
<div class="user-card">
    <div class="user-card-header">Wallet History</div>
    <div class="user-card-body-flush">
        <div class="table-responsive">
            <table class="table user-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Description</th>
                        <th>Date</th>
                        <th class="pe-4 text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($walletTransactions as $tx)
                        <tr>
                            <td class="ps-4">{{ $tx->description }}</td>
                            <td>{{ $tx->created_at->format('d M Y, h:i A') }}</td>
                            <td class="pe-4 text-end fw-bold" style="color:{{ $tx->type === 'credit' ? 'var(--up-success)' : 'var(--up-danger)' }};">
                                {{ $tx->type === 'credit' ? '+' : '-' }} ₹ {{ number_format($tx->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="user-empty border-0">
                                No wallet activity yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyText(id, btn) {
        const el = document.getElementById(id);
        el.select();
        el.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(el.value).then(function () {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="far fa-check"></i> Copied!';
            setTimeout(function () { btn.innerHTML = original; }, 1500);
        });
    }
</script>
@endpush

@endsection
