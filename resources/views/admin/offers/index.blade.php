@extends('layouts.admin')

@section('content')

{{-- Tailwind CDN --}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- Bootstrap Icons --}}
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>
    .offer-card {
        transition:
            transform .35s ease,
            box-shadow .35s ease,
            border-color .35s ease;
    }

    .offer-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 55px rgba(15, 23, 42, .12);
        border-color: #dbe3f0;
    }

    .offer-image {
        transition: transform .6s cubic-bezier(.2,.8,.2,1);
    }

    .offer-card:hover .offer-image {
        transform: scale(1.07);
    }

    .action-btn {
        transition: all .25s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .premium-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .premium-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .premium-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
    }
</style>


<div class="min-h-screen bg-[#f7f9fc]">

    <div class="mx-auto w-full px-4 py-6 sm:px-6 lg:px-8">

        {{-- =====================================================
             HEADER
        ====================================================== --}}
        <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-4">

                {{-- Icon --}}
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center
                           rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600
                           text-white shadow-xl shadow-indigo-200"
                >
                    <i class="bi bi-gift-fill text-2xl"></i>
                </div>

                <div>

                    <div class="flex items-center gap-3">

                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                            Offers
                        </h1>

                        <span
                            class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-bold text-indigo-600"
                        >
                            {{ $offers->count() }} Offers
                        </span>

                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        Create and manage your promotional offers
                    </p>

                </div>

            </div>


            {{-- Add Offer --}}
            <a
                href="{{ route('admin.offers.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       bg-gradient-to-r from-indigo-600 to-violet-600
                       px-5 py-3 text-sm font-bold text-white
                       shadow-lg shadow-indigo-200
                       transition-all duration-300
                       hover:-translate-y-1 hover:shadow-xl"
            >
                <i class="bi bi-plus-lg"></i>
                Add Offer
            </a>

        </div>



        {{-- =====================================================
             OFFER CARDS
        ====================================================== --}}
        @if($offers->count())

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

                @foreach($offers as $offer)

                    @php
                        $image = $offer->getFirstMediaUrl('offer_image');

                        $conditionLabel = match($offer->condition_type) {
                            'price' => 'Minimum Purchase',
                            'kg' => 'Minimum Quantity',
                            'piece' => 'Minimum Pieces',
                            default => 'Required Quantity',
                        };

                        $conditionValue = match($offer->condition_type) {
                            'price' => '₹' . number_format((float) $offer->condition_value, 2),
                            'kg' => $offer->condition_value . ' KG',
                            'piece' => $offer->condition_value . ' Pieces',
                            default => $offer->condition_value . ' Qty',
                        };

                        $appliesToLabel = [
                            'all' => 'All Products',
                            'egg' => 'Eggs Only',
                            'hen' => 'Hens Only',
                        ][$offer->applies_to] ?? 'All Products';

                        $rewardType = 'Wallet Cashback';

                        $rewardValue = $offer->reward_kind === 'percent'
                            ? rtrim(rtrim($offer->reward_value, '0'), '.') . '%'
                            : '₹' . number_format($offer->reward_value, 2);
                    @endphp


                    {{-- =================================================
                         CARD
                    ================================================== --}}
                    <div
                        class="offer-card overflow-hidden rounded-[26px]
                               border border-slate-200/80 bg-white
                               shadow-[0_10px_35px_rgba(15,23,42,0.06)]"
                    >

                        {{-- =================================================
                             IMAGE
                        ================================================== --}}
                        <div class="relative h-[235px] overflow-hidden bg-slate-100">

                            @if($image)

                                <img
                                    src="{{ $image }}"
                                    alt="{{ $offer->title }}"
                                    class="offer-image h-full w-full object-cover"
                                    loading="lazy"
                                >

                            @else

                                <div
                                    class="flex h-full w-full items-center justify-center
                                           bg-gradient-to-br from-indigo-50
                                           via-white to-violet-100"
                                >

                                    <div class="text-center">

                                        <div
                                            class="mx-auto mb-3 flex h-16 w-16 items-center
                                                   justify-center rounded-2xl
                                                   bg-white text-indigo-500 shadow-md"
                                        >
                                            <i class="bi bi-gift-fill text-3xl"></i>
                                        </div>

                                        <span class="text-xs font-bold text-slate-400">
                                            No Offer Image
                                        </span>

                                    </div>

                                </div>

                            @endif


                            {{-- Image Gradient --}}
                            <div
                                class="absolute inset-0
                                       bg-gradient-to-t from-slate-950/75
                                       via-slate-950/10 to-transparent"
                            ></div>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            @if($offer->status)

                                <span
                                    class="absolute right-4 top-4 inline-flex items-center
                                           gap-1.5 rounded-full
                                           border border-white/20
                                           bg-emerald-500/90
                                           px-3.5 py-1.5
                                           text-xs font-bold text-white
                                           shadow-lg backdrop-blur-md"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-white"
                                    ></span>

                                    Active
                                </span>

                            @else

                                <span
                                    class="absolute right-4 top-4 inline-flex items-center
                                           gap-1.5 rounded-full
                                           border border-white/20
                                           bg-slate-800/80
                                           px-3.5 py-1.5
                                           text-xs font-bold text-white
                                           shadow-lg backdrop-blur-md"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-red-400"
                                    ></span>

                                    Inactive
                                </span>

                            @endif


                            {{-- =================================================
                                 OFFER TITLE
                            ================================================== --}}
                            <div class="absolute bottom-5 left-5 right-5">

                                <h2
                                    class="text-xl font-extrabold leading-tight
                                           tracking-tight text-white
                                           drop-shadow-lg"
                                >
                                    {{ $offer->title }}
                                </h2>

                                @if($offer->description)

                                    <p
                                        class="mt-1 line-clamp-1 text-xs
                                               text-white/75"
                                    >
                                        {{ $offer->description }}
                                    </p>

                                @endif

                            </div>

                        </div>


                        {{-- =================================================
                             BODY
                        ================================================== --}}
                        <div class="p-5">


                            {{-- =================================================
                                 CONDITION + REWARD
                            ================================================== --}}
                            <div class="grid grid-cols-2 gap-3">

                                {{-- CONDITION --}}
                                <div
                                    class="rounded-2xl border border-slate-100
                                           bg-slate-50 p-4"
                                >

                                    <div class="flex items-center gap-2">

                                        <div
                                            class="flex h-8 w-8 shrink-0 items-center
                                                   justify-center rounded-lg
                                                   bg-indigo-100 text-indigo-600"
                                        >
                                            <i class="bi bi-bullseye"></i>
                                        </div>

                                        <span
                                            class="truncate text-[10px] font-bold
                                                   uppercase tracking-wider
                                                   text-slate-400"
                                        >
                                            Condition
                                        </span>

                                    </div>

                                    <p
                                        class="mt-3 text-sm font-extrabold
                                               text-slate-800"
                                    >
                                        {{ $conditionValue }}
                                    </p>

                                    <p
                                        class="mt-1 truncate text-[11px]
                                               text-slate-400"
                                    >
                                        {{ $conditionLabel }}
                                    </p>

                                </div>


                                {{-- REWARD --}}
                                <div
                                    class="rounded-2xl border border-slate-100
                                           bg-slate-50 p-4"
                                >

                                    <div class="flex items-center gap-2">

                                        <div
                                            class="flex h-8 w-8 shrink-0 items-center
                                                   justify-center rounded-lg
                                                   bg-violet-100 text-violet-600"
                                        >
                                            <i class="bi bi-wallet2"></i>
                                        </div>

                                        <span
                                            class="truncate text-[10px] font-bold
                                                   uppercase tracking-wider
                                                   text-slate-400"
                                        >
                                            {{ $rewardType }}
                                        </span>

                                    </div>

                                    <p
                                        class="mt-3 truncate text-sm font-extrabold
                                               text-slate-800"
                                    >
                                        {{ $rewardValue }}
                                    </p>

                                    <p
                                        class="mt-1 truncate text-[11px]
                                               font-semibold text-slate-400"
                                    >
                                        Applies to: {{ $appliesToLabel }}
                                    </p>

                                </div>

                            </div>


                            {{-- =================================================
                                 VALIDITY
                            ================================================== --}}
                            <div
                                class="mt-4 rounded-2xl border border-slate-100
                                       bg-slate-50 p-4"
                            >

                                <div class="mb-3 flex items-center gap-2">

                                    <div
                                        class="flex h-8 w-8 items-center justify-center
                                               rounded-lg bg-sky-100 text-sky-600"
                                    >
                                        <i class="bi bi-calendar3"></i>
                                    </div>

                                    <span
                                        class="text-xs font-bold uppercase
                                               tracking-wider text-slate-400"
                                    >
                                        Offer Validity
                                    </span>

                                </div>


                                <div class="flex items-center justify-between gap-3">

                                    {{-- START --}}
                                    <div>

                                        <p
                                            class="text-[10px] font-bold uppercase
                                                   tracking-wider text-slate-400"
                                        >
                                            Starts
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-bold
                                                   text-slate-800"
                                        >
                                            {{ $offer->start_date?->format('d M Y') ?? 'N/A' }}
                                        </p>

                                    </div>


                                    {{-- LINE --}}
                                    <div class="flex flex-1 items-center gap-2">

                                        <div class="h-px flex-1 bg-slate-200"></div>

                                        <div
                                            class="flex h-7 w-7 shrink-0 items-center
                                                   justify-center rounded-full
                                                   bg-white text-slate-400
                                                   shadow-sm"
                                        >
                                            <i class="bi bi-arrow-right text-xs"></i>
                                        </div>

                                        <div class="h-px flex-1 bg-slate-200"></div>

                                    </div>


                                    {{-- END --}}
                                    <div class="text-right">

                                        <p
                                            class="text-[10px] font-bold uppercase
                                                   tracking-wider text-slate-400"
                                        >
                                            Ends
                                        </p>

                                        <p
                                            class="mt-1 text-sm font-bold
                                                   text-slate-800"
                                        >
                                            {{ $offer->end_date?->format('d M Y') ?? 'N/A' }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}
                        <div
                            class="border-t border-slate-100 bg-white p-5"
                        >

                            <div class="grid grid-cols-2 gap-3">

                                {{-- EDIT --}}
                                <a
                                    href="{{ route('admin.offers.edit', $offer->id) }}"
                                    class="action-btn inline-flex items-center
                                           justify-center gap-2 rounded-xl
                                           bg-amber-50 px-4 py-3
                                           text-sm font-bold text-amber-700
                                           hover:bg-amber-500 hover:text-white"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                    Edit
                                </a>


                                {{-- DELETE --}}
                                <form
                                    method="POST"
                                    action="{{ route('admin.offers.destroy', $offer->id) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this offer?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="action-btn inline-flex w-full
                                               items-center justify-center gap-2
                                               rounded-xl bg-red-50 px-4 py-3
                                               text-sm font-bold text-red-600
                                               hover:bg-red-500 hover:text-white"
                                    >
                                        <i class="bi bi-trash3"></i>
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            {{-- =====================================================
                 EMPTY STATE
            ====================================================== --}}
            <div
                class="rounded-[28px] border border-slate-200
                       bg-white px-6 py-20 text-center
                       shadow-[0_10px_35px_rgba(15,23,42,0.05)]"
            >

                <div
                    class="mx-auto flex h-20 w-20 items-center justify-center
                           rounded-3xl bg-indigo-50 text-indigo-600"
                >
                    <i class="bi bi-gift text-4xl"></i>
                </div>

                <h2
                    class="mt-6 text-xl font-extrabold text-slate-900"
                >
                    No Offers Yet
                </h2>

                <p
                    class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500"
                >
                    Create your first promotional offer and start giving
                    attractive deals to your customers.
                </p>

                <a
                    href="{{ route('admin.offers.create') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-xl
                           bg-gradient-to-r from-indigo-600 to-violet-600
                           px-5 py-3 text-sm font-bold text-white
                           shadow-lg shadow-indigo-200
                           transition hover:-translate-y-1"
                >
                    <i class="bi bi-plus-lg"></i>
                    Create First Offer
                </a>

            </div>

        @endif

    </div>

</div>

@endsection