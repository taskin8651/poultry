@extends('layouts.admin')

@section('content')

<div class="mx-auto w-full max-w-2xl">

    {{-- =========================================
         HEADER
    ========================================== --}}
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-tags-fill"></i>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">
                    Add Tag
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Create a new product tag
                </p>
            </div>

        </div>

        <a href="{{ route('admin.tags.index') }}" class="btn-premium btn-premium-outline">
            <i class="bi bi-arrow-left"></i>
            Back to Tags
        </a>

    </div>


    {{-- =========================================
         FORM CARD
    ========================================== --}}
    <div class="card-premium overflow-hidden">

        <form action="{{ route('admin.tags.store') }}" method="POST">
            @csrf

            <div class="p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="bi bi-hash"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Tag Details
                        </h2>
                        <p class="text-sm text-slate-500">
                            Give your tag a short, descriptive name.
                        </p>
                    </div>

                </div>

                <div>
                    <label for="name" class="label-premium">Tag Name</label>
                    <input type="text" id="name" name="name"
                           placeholder="e.g. Organic"
                           class="input-premium">
                    @error('name')
                        <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- =========================================
                 FOOTER
            ========================================== --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8">

                <a href="{{ route('admin.tags.index') }}" class="btn-premium btn-premium-outline">
                    Cancel
                </a>

                <button type="submit" class="btn-premium btn-premium-primary">
                    <i class="bi bi-check2-circle"></i>
                    Save Tag
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
