@extends('layouts.admin')

@section('content')

<div class="mx-auto w-full max-w-3xl">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                    {{ $contact->name }}
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Received {{ $contact->created_at?->format('d M Y, h:i A') }}
                </p>
            </div>

        </div>

        <a href="{{ route('admin.contacts.index') }}"
           class="btn-premium btn-premium-outline">
            <i class="bi bi-arrow-left"></i>
            Back to Messages
        </a>

    </div>


    {{-- =====================================================
         CONTACT INFO
    ====================================================== --}}
    <div class="card-premium mb-6 p-6 sm:p-8">

        <h2 class="mb-5 text-lg font-extrabold text-slate-900">
            Contact Information
        </h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Name
                </p>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $contact->name }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Email
                </p>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $contact->email }}
                </p>
            </div>

            <div class="rounded-2xl bg-slate-50 p-4 sm:col-span-2">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    Phone
                </p>
                <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $contact->phone ?? '-' }}
                </p>
            </div>

        </div>

    </div>


    {{-- =====================================================
         MESSAGE
    ====================================================== --}}
    <div class="card-premium p-6 sm:p-8">

        <div class="mb-4 flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-chat-square-text-fill"></i>
            </div>

            <h2 class="text-lg font-extrabold text-slate-900">
                Message
            </h2>

        </div>

        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
            <p class="whitespace-pre-line text-sm leading-6 text-slate-700">
                {{ $contact->message }}
            </p>
        </div>

    </div>

</div>

@endsection
