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
                <i class="bi bi-chat-quote-fill"></i>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">
                    Add Testimonial
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Add a new customer testimonial
                </p>
            </div>

        </div>


        <a href="{{ route('admin.testimonials.index') }}" class="btn-premium btn-premium-outline">
            <i class="bi bi-arrow-left"></i>
            Back to Testimonials
        </a>

    </div>


    {{-- =========================================
         FORM CARD
    ========================================== --}}
    <div class="card-premium overflow-hidden">

        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('admin.testimonials.store') }}"
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
                        Enter the customer's name and role.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Name --}}
                    <div>

                        <label for="name" class="label-premium">
                            Name
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="e.g. Rajesh Kumar"
                            class="input-premium"
                        >

                        @error('name')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Position --}}
                    <div>

                        <label for="position" class="label-premium">
                            Position
                        </label>

                        <input
                            id="position"
                            type="text"
                            name="position"
                            value="{{ old('position') }}"
                            placeholder="e.g. Farm Owner"
                            class="input-premium"
                        >

                        @error('position')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =========================================
                 MESSAGE + RATING
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="bi bi-chat-quote"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Testimonial
                        </h2>

                        <p class="text-sm text-slate-500">
                            What did the customer say about you?
                        </p>
                    </div>

                </div>


                {{-- Message --}}
                <div class="mb-5">

                    <label for="message" class="label-premium">
                        Message
                    </label>

                    <textarea
                        id="message"
                        name="message"
                        rows="4"
                        placeholder="Write the customer's testimonial..."
                        class="input-premium resize-none"
                    >{{ old('message') }}</textarea>

                    @error('message')
                        <p class="mt-1 text-xs font-medium text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Rating --}}
                <div class="sm:w-1/3">

                    <label for="rating" class="label-premium">
                        Rating (1-5)
                    </label>

                    <input
                        id="rating"
                        type="number"
                        name="rating"
                        min="1"
                        max="5"
                        value="{{ old('rating') }}"
                        placeholder="e.g. 5"
                        class="input-premium"
                    >

                    @error('rating')
                        <p class="mt-1 text-xs font-medium text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

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
                            Customer Photo
                        </label>

                        <label
                            for="image"
                            class="upload-box flex min-h-[170px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-5 text-center"
                        >

                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                                <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                            </div>

                            <span class="text-sm font-bold text-slate-700">
                                Upload Photo
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
                            Status
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
                                    Active testimonials will be displayed on the public website.
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

                <a href="{{ route('admin.testimonials.index') }}" class="btn-premium btn-premium-outline">
                    Cancel
                </a>

                <button type="submit" class="btn-premium btn-premium-primary">
                    <i class="bi bi-check2-circle"></i>
                    Save Testimonial
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
