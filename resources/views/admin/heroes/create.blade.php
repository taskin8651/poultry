@extends('layouts.admin')

@section('content')

<style>
    .upload-box {
        transition: all .25s ease;
    }

    .upload-box:hover {
        border-color: #6366f1;
        background: #f8faff;
    }
</style>


<div class="mx-auto max-w-4xl">

    {{-- =========================================
         HEADER
    ========================================== --}}
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-image"></i>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">
                    Add Hero
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Create a new homepage hero slide
                </p>
            </div>

        </div>


        <a href="{{ route('admin.heroes.index') }}" class="btn-premium btn-premium-outline">
            <i class="bi bi-arrow-left"></i>
            Back to Heroes
        </a>

    </div>


    {{-- =========================================
         FORM CARD
    ========================================== --}}
    <div class="card-premium overflow-hidden">

        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('admin.heroes.store') }}"
        >

            @csrf


            {{-- =========================================
                 BASIC INFORMATION
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6">

                    <h2 class="text-lg font-extrabold text-slate-900">
                        Basic Information
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Enter the main headline of your hero slide.
                    </p>

                </div>


                {{-- Title --}}
                <div class="mb-5">

                    <label for="title" class="label-premium">
                        Title
                    </label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="e.g. Fresh Poultry, Delivered Daily"
                        class="input-premium"
                    >

                    @error('title')
                        <p class="mt-1 text-xs font-medium text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Subtitle --}}
                <div>

                    <label for="subtitle" class="label-premium">
                        Subtitle
                    </label>

                    <input
                        id="subtitle"
                        type="text"
                        name="subtitle"
                        value="{{ old('subtitle') }}"
                        placeholder="e.g. 100% Farm Fresh Quality"
                        class="input-premium"
                    >

                    @error('subtitle')
                        <p class="mt-1 text-xs font-medium text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- =========================================
                 CALL TO ACTION
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="bi bi-mouse2"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Call To Action
                        </h2>

                        <p class="text-sm text-slate-500">
                            Configure the button shown on the slide.
                        </p>
                    </div>

                </div>


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Button Text --}}
                    <div>

                        <label for="button_text" class="label-premium">
                            Button Text
                        </label>

                        <input
                            id="button_text"
                            type="text"
                            name="button_text"
                            value="{{ old('button_text') }}"
                            placeholder="e.g. Shop Now"
                            class="input-premium"
                        >

                        @error('button_text')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Button Link --}}
                    <div>

                        <label for="button_link" class="label-premium">
                            Button Link
                        </label>

                        <input
                            id="button_link"
                            type="text"
                            name="button_link"
                            value="{{ old('button_link') }}"
                            placeholder="e.g. /products"
                            class="input-premium"
                        >

                        @error('button_link')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =========================================
                 IMAGE + STATUS
            ========================================== --}}
            <div class="p-6 sm:p-8">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- IMAGE --}}
                    <div>

                        <label class="label-premium">
                            Hero Image
                        </label>

                        <label
                            for="image"
                            class="upload-box flex min-h-[170px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-5 text-center"
                        >

                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                                <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                            </div>

                            <span class="text-sm font-bold text-slate-700">
                                Upload Hero Image
                            </span>

                            <span class="mt-1 text-xs text-slate-400">
                                JPG, JPEG, PNG or WEBP
                            </span>

                            <input
                                id="image"
                                type="file"
                                name="image"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                                class="hidden"
                            >

                        </label>

                        <p id="image-name" class="mt-2 hidden text-xs font-semibold text-indigo-600"></p>

                        @error('image')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- STATUS --}}
                    <div>

                        <label for="status" class="label-premium">
                            Slide Status
                        </label>

                        <select id="status" name="status" class="input-premium">

                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        <div class="mt-4 rounded-2xl bg-slate-50 p-4">

                            <div class="flex items-start gap-3">

                                <i class="bi bi-info-circle mt-0.5 text-indigo-500"></i>

                                <p class="text-xs leading-5 text-slate-500">
                                    Active slides will be displayed in the homepage hero slider.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================
                 FOOTER
            ========================================== --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8">

                <a href="{{ route('admin.heroes.index') }}" class="btn-premium btn-premium-outline">
                    Cancel
                </a>

                <button type="submit" class="btn-premium btn-premium-primary">
                    <i class="bi bi-check2-circle"></i>
                    Save Hero
                </button>

            </div>

        </form>

    </div>

</div>


<script>
    const imageInput = document.getElementById('image');
    const imageName = document.getElementById('image-name');

    if (imageInput) {

        imageInput.addEventListener('change', function () {

            if (this.files.length > 0) {

                imageName.textContent = 'Selected: ' + this.files[0].name;
                imageName.classList.remove('hidden');

            } else {

                imageName.textContent = '';
                imageName.classList.add('hidden');

            }

        });

    }
</script>

@endsection
