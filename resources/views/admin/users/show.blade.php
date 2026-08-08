@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-person-lines-fill"></i>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                {{ trans('global.show') }} {{ trans('cruds.user.title_singular') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                View user details and assigned roles
            </p>
        </div>
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn-premium btn-premium-outline">
        <i class="bi bi-arrow-left"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- MAIN CARD --}}
<div class="card-premium max-w-4xl overflow-hidden">

    <div class="p-6 sm:p-8">

        {{-- USER HEADER --}}
        <div class="mb-8 flex items-center gap-5">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl
                        bg-gradient-to-br from-indigo-600 to-violet-600
                        text-2xl font-extrabold text-white shadow-lg shadow-indigo-200">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>
                <h2 class="text-xl font-extrabold text-slate-900">
                    {{ $user->name }}
                </h2>
                <p class="text-sm text-slate-500">
                    {{ $user->email }}
                </p>
            </div>
        </div>

        {{-- USER DETAILS --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            {{-- USER ID --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.user.fields.id') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    #{{ $user->id }}
                </p>
            </div>

            {{-- EMAIL VERIFIED --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.user.fields.email_verified_at') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    @if($user->email_verified_at)
                        {{ $user->email_verified_at->format('d M Y, H:i') }}
                    @else
                        <span class="badge-premium badge-neutral">Not verified</span>
                    @endif
                </p>
            </div>

            {{-- REFERRAL CODE --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Referral Code
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $user->referral_code ?? '—' }}
                </p>
            </div>

            {{-- WALLET BALANCE --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Wallet Balance
                </p>
                <p class="mt-1 text-sm font-extrabold text-emerald-600">
                    ₹{{ number_format($user->wallet_balance, 2) }}
                </p>
            </div>

            {{-- REFERRED BY --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Referred By
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $user->referrer->name ?? '—' }}
                </p>
            </div>

        </div>

        {{-- ROLES --}}
        <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 p-4">
            <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                {{ trans('cruds.user.fields.roles') }}
            </p>

            @if($user->roles->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($user->roles as $role)
                        <span class="badge-premium badge-info">
                            {{ $role->title }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500">
                    No roles assigned
                </p>
            @endif
        </div>

    </div>

    {{-- FOOTER ACTIONS --}}
    <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-5 sm:px-8">

        <a href="{{ route('admin.users.index') }}" class="btn-premium btn-premium-outline">
            {{ trans('global.back_to_list') }}
        </a>

        @can('user_edit')
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-premium btn-premium-primary">
                <i class="bi bi-pencil-square"></i>
                {{ trans('global.edit') }}
            </a>
        @endcan

    </div>
</div>

@endsection
