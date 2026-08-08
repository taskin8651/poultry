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

    .item-image {
        transition: transform .6s cubic-bezier(.2,.8,.2,1);
    }

    .item-card:hover .item-image {
        transform: scale(1.07);
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
                <i class="bi bi-gear-wide-connected"></i>
            </div>

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Services
                    </h1>

                    <span class="badge-premium badge-info">
                        {{ $services->count() }} Services
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Manage the services displayed on your website
                </p>

            </div>

        </div>


        <a href="{{ route('admin.services.create') }}" class="btn-premium btn-premium-primary">
            <i class="bi bi-plus-lg"></i>
            Add Service
        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}
    @if(session('success'))

        <div class="alert-premium-success mb-6">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <i class="bi bi-check-lg"></i>
            </div>

            <span>{{ session('success') }}</span>
        </div>

    @endif


    {{-- =====================================================
         SERVICE CARDS
    ====================================================== --}}
    @if($services->count())

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">

            @foreach($services as $service)

                @php
                    $image = $service->getFirstMediaUrl('service_image');
                @endphp

                <div class="item-card card-premium overflow-hidden rounded-[26px]">

                    {{-- IMAGE --}}
                    <div class="relative h-[200px] overflow-hidden bg-slate-100">

                        @if($image)

                            <img
                                src="{{ $image }}"
                                alt="{{ $service->title }}"
                                class="item-image h-full w-full object-cover"
                                loading="lazy"
                            >

                        @else

                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-violet-100">

                                <div class="text-center">

                                    <div class="mx-auto mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-indigo-500 shadow-md">
                                        <i class="bi bi-gear-wide-connected text-3xl"></i>
                                    </div>

                                    <span class="text-xs font-bold text-slate-400">
                                        No Image
                                    </span>

                                </div>

                            </div>

                        @endif


                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/10 to-transparent"></div>


                        {{-- STATUS --}}
                        @if($service->status)

                            <span class="badge-premium badge-success absolute right-4 top-4 border border-white/20 !bg-emerald-500/90 !text-white shadow-lg backdrop-blur-md">
                                <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                Active
                            </span>

                        @else

                            <span class="badge-premium absolute right-4 top-4 border border-white/20 !bg-slate-800/80 !text-white shadow-lg backdrop-blur-md">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                Inactive
                            </span>

                        @endif


                        {{-- TITLE --}}
                        <div class="absolute bottom-5 left-5 right-5">

                            <h2 class="text-xl font-extrabold leading-tight tracking-tight text-white drop-shadow-lg">
                                {{ $service->title }}
                            </h2>

                        </div>

                    </div>


                    {{-- BODY --}}
                    <div class="p-5">

                        <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">

                            <div class="flex items-center gap-2">

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                    <i class="bi bi-file-text"></i>
                                </div>

                                <span class="truncate text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    Description
                                </span>

                            </div>

                            <p class="mt-3 line-clamp-3 text-sm text-slate-700">
                                {{ $service->description ?: 'No description provided.' }}
                            </p>

                        </div>

                    </div>


                    {{-- ACTIONS --}}
                    <div class="border-t border-slate-100 bg-white p-5">

                        <div class="grid grid-cols-2 gap-3">

                            <a href="{{ route('admin.services.edit', $service->id) }}" class="action-btn btn-premium btn-premium-amber">
                                <i class="bi bi-pencil-square"></i>
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.services.destroy', $service->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this service?');"
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

                </div>

            @endforeach

        </div>

    @else

        {{-- =====================================================
             EMPTY STATE
        ====================================================== --}}
        <div class="card-premium rounded-[28px] px-6 py-20 text-center">

            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-gear-wide-connected text-4xl"></i>
            </div>

            <h2 class="mt-6 text-xl font-extrabold text-slate-900">
                No Services Yet
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                Create your first service to showcase what you offer to customers.
            </p>

            <a href="{{ route('admin.services.create') }}" class="btn-premium btn-premium-primary mt-6">
                <i class="bi bi-plus-lg"></i>
                Create First Service
            </a>

        </div>

    @endif

</div>

@endsection
