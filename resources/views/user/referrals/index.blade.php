@extends('user.layout')

@section('account-title', 'Refer & Earn')

@section('account-content')

@php
    $referralLink = url('/register?ref=' . $user->referral_code);
    $shareText = 'Join and shop with me! Use my referral code ' . $user->referral_code . ' when you sign up: ' . $referralLink;
@endphp

<div class="row g-4 mb-4">

    {{-- REFERRAL CODE / SHARE CARD --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-body p-4">
                <h4 class="mb-2"><i class="far fa-gift text-warning"></i> Refer friends, earn wallet cash</h4>
                <p class="text-muted mb-4">
                    Share your referral code with friends. When someone signs up with your code
                    and places their first order, you get rewarded straight into your wallet.
                </p>

                <label class="fw-bold mb-2">Your Referral Code</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control fw-bold text-uppercase" id="referral-code" value="{{ $user->referral_code }}" readonly>
                    <button class="theme-btn" type="button" onclick="copyText('referral-code', this)">
                        <i class="far fa-copy"></i> Copy Code
                    </button>
                </div>

                <label class="fw-bold mb-2">Your Referral Link</label>
                <div class="input-group mb-4">
                    <input type="text" class="form-control" id="referral-link" value="{{ $referralLink }}" readonly>
                    <button class="theme-btn" type="button" onclick="copyText('referral-link', this)">
                        <i class="far fa-link"></i> Copy Link
                    </button>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a class="theme-btn" target="_blank"
                       href="https://wa.me/?text={{ urlencode($shareText) }}">
                        <i class="fab fa-whatsapp"></i> Share on WhatsApp
                    </a>
                    <a class="theme-btn" target="_blank"
                       href="mailto:?subject={{ urlencode('Join me and get rewards') }}&body={{ urlencode($shareText) }}">
                        <i class="far fa-envelope"></i> Share via Email
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- WALLET BALANCE CARD --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100 text-white" style="border-radius:14px;background:linear-gradient(135deg,#EE7D21,#f7a24d);">
            <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                <i class="far fa-wallet fa-2x mb-3"></i>
                <p class="mb-1 opacity-75">Wallet Balance</p>
                <h2 class="fw-bold mb-0">₹ {{ number_format($user->wallet_balance, 2) }}</h2>
                <p class="mt-3 mb-0 small opacity-75">
                    Used automatically at checkout when you tick "Use wallet balance".
                </p>
            </div>
        </div>
    </div>

</div>

{{-- STATS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 user-stat-card">
            <h4 class="mb-0">{{ $referrals->count() }}</h4>
            <small class="text-muted">Total Invited</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 user-stat-card">
            <h4 class="mb-0 text-success">{{ $referrals->where('status', 'rewarded')->count() }}</h4>
            <small class="text-muted">Rewarded</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 user-stat-card">
            <h4 class="mb-0 text-warning">{{ $referrals->where('status', 'pending')->count() }}</h4>
            <small class="text-muted">Awaiting First Order</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 user-stat-card">
            <h4 class="mb-0" style="color:#EE7D21;">₹ {{ number_format($referrals->where('status', 'rewarded')->sum('reward_amount'), 2) }}</h4>
            <small class="text-muted">Total Earned</small>
        </div>
    </div>
</div>

{{-- REFERRALS LIST --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:14px;">
    <div class="card-header bg-white fw-bold">People You've Invited</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="bg-light">
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
                                    <span class="badge bg-success">Rewarded</span>
                                @else
                                    <span class="badge bg-warning text-dark">Awaiting First Order</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end fw-bold">
                                {{ $referral->reward_amount ? '₹ ' . number_format($referral->reward_amount, 2) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
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
<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-header bg-white fw-bold">Wallet History</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="bg-light">
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
                            <td class="pe-4 text-end fw-bold {{ $tx->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $tx->type === 'credit' ? '+' : '-' }} ₹ {{ number_format($tx->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
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
