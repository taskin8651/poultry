@extends('layouts.admin')

@section('content')

<style>
    .item-card {
        transition:
            transform .35s ease,
            box-shadow .35s ease,
            border-color .35s ease;
    }

    .item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 55px rgba(15, 23, 42, .12);
        border-color: #dbe3f0;
    }

    .action-btn {
        transition: all .25s ease;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>


<div class="mx-auto w-full">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-chat-quote-fill"></i>
            </div>

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Testimonials
                    </h1>

                    <span class="badge-premium badge-info">
                        {{ $testimonials->count() }} Testimonials
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Manage customer testimonials shown on your website
                </p>

            </div>

        </div>


        <a href="{{ route('admin.testimonials.create') }}" class="btn-premium btn-premium-primary">
            <i class="bi bi-plus-lg"></i>
            Add Testimonial
        </a>

    </div>


    {{-- =====================================================
         TESTIMONIAL CARDS
    ====================================================== --}}
    @if($testimonials->count())

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

            @foreach($testimonials as $t)

                @php
                    $image = $t->getFirstMediaUrl('testimonial_image');
                    $rating = (int) $t->rating;
                @endphp

                <div class="item-card card-premium relative overflow-hidden rounded-[26px] p-6">

                    {{-- STATUS --}}
                    @if($t->status)

                        <span class="badge-premium badge-success absolute right-5 top-5">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Active
                        </span>

                    @else

                        <span class="badge-premium badge-danger absolute right-5 top-5">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                            Inactive
                        </span>

                    @endif


                    {{-- AVATAR + NAME --}}
                    <div class="flex items-center gap-4">

                        <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-slate-100 bg-slate-100 shadow-sm">

                            @if($image)

                                <img
                                    src="{{ $image }}"
                                    alt="{{ $t->name }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >

                            @else

                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-violet-100 text-indigo-500">
                                    <i class="bi bi-person-fill text-2xl"></i>
                                </div>

                            @endif

                        </div>

                        <div class="min-w-0">

                            <h2 class="truncate text-base font-extrabold text-slate-900">
                                {{ $t->name }}
                            </h2>

                            <p class="truncate text-xs font-semibold text-slate-400">
                                {{ $t->position ?: 'Customer' }}
                            </p>

                        </div>

                    </div>


                    {{-- RATING --}}
                    <div class="mt-4 flex items-center gap-1">

                        @for($i = 1; $i <= 5; $i++)

                            <i class="bi {{ $i <= $rating ? 'bi-star-fill text-amber-400' : 'bi-star text-slate-200' }}"></i>

                        @endfor

                        <span class="ml-1 text-xs font-bold text-slate-400">
                            {{ $rating }}/5
                        </span>

                    </div>


                    {{-- MESSAGE --}}
                    <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4">

                        <i class="bi bi-quote text-lg text-indigo-300"></i>

                        <p class="mt-1 line-clamp-3 text-sm italic text-slate-600">
                            {{ $t->message ?: 'No message provided.' }}
                        </p>

                    </div>


                    {{-- ACTIONS --}}
                    <div class="mt-5 grid grid-cols-2 gap-3">

                        <a href="{{ route('admin.testimonials.edit', $t->id) }}" class="action-btn btn-premium btn-premium-amber">
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </a>

                        <form
                            method="POST"
                            action="{{ route('admin.testimonials.destroy', $t->id) }}"
                            onsubmit="return confirm('Are you sure you want to delete this testimonial?');"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="action-btn btn-premium btn-premium-danger w-full">
                                <i class="bi bi-trash3"></i>
                                Delete
                            </button>
                        </form>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- =====================================================
             EMPTY STATE
        ====================================================== --}}
        <div class="card-premium rounded-[28px] px-6 py-20 text-center">

            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-chat-quote-fill text-4xl"></i>
            </div>

            <h2 class="mt-6 text-xl font-extrabold text-slate-900">
                No Testimonials Yet
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                Add your first customer testimonial to build trust with visitors.
            </p>

            <a href="{{ route('admin.testimonials.create') }}" class="btn-premium btn-premium-primary mt-6">
                <i class="bi bi-plus-lg"></i>
                Create First Testimonial
            </a>

        </div>

    @endif

</div>

@endsection
