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

    .preview-image {
        transition: transform .3s ease;
    }

    .preview-image:hover {
        transform: scale(1.02);
    }
</style>


<div class="mx-auto max-w-4xl">

    {{-- =========================================
         HEADER
    ========================================== --}}
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">
                    Edit Service
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Update service details and settings
                </p>
            </div>

        </div>


        <a href="{{ route('admin.services.index') }}" class="btn-premium btn-premium-outline">
            <i class="bi bi-arrow-left"></i>
            Back to Services
        </a>

    </div>


    {{-- =========================================
         FORM
    ========================================== --}}
    <div class="card-premium overflow-hidden">

        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ route('admin.services.update', $service->id) }}"
        >

            @csrf
            @method('PUT')


            {{-- =========================================
                 BASIC INFORMATION
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6">

                    <h2 class="text-lg font-extrabold text-slate-900">
                        Basic Information
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Update the name of this service.
                    </p>

                </div>


                {{-- Title --}}
                <div>

                    <label for="title" class="label-premium">
                        Service Title
                    </label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title', $service->title) }}"
                        class="input-premium"
                    >

                    @error('title')
                        <p class="mt-1 text-xs font-medium text-red-500">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- =========================================
                 DESCRIPTION
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="bi bi-file-text"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Description
                        </h2>

                        <p class="text-sm text-slate-500">
                            Update what this service includes.
                        </p>
                    </div>

                </div>


                <div>

                    <label for="description" class="label-premium">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        class="input-premium resize-none"
                    >{{ old('description', $service->description) }}</textarea>

                    @error('description')
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
                            Service Image
                        </label>

                        @php
                            $currentImage = $service->getFirstMediaUrl('service_image');
                        @endphp


                        @if($currentImage)

                            <div class="mb-4 overflow-hidden rounded-2xl border border-slate-200">

                                <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50 px-4 py-3">

                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Current Image
                                    </span>

                                    <span class="badge-premium badge-success">
                                        Uploaded
                                    </span>

                                </div>

                                <img
                                    src="{{ $currentImage }}"
                                    alt="{{ $service->title }}"
                                    class="preview-image h-48 w-full object-cover"
                                >

                            </div>

                        @endif


                        <label
                            for="image"
                            class="upload-box flex min-h-[145px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-5 text-center"
                        >

                            <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                                <i class="bi bi-cloud-arrow-up-fill text-lg"></i>
                            </div>

                            <span class="text-sm font-bold text-slate-700">
                                {{ $currentImage ? 'Replace Image' : 'Upload Image' }}
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
                            Service Status
                        </label>

                        <select id="status" name="status" class="input-premium">

                            <option value="1" {{ $service->status ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ !$service->status ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>


                        <div class="mt-4 rounded-2xl bg-slate-50 p-5">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $service->status ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                                    <i class="bi {{ $service->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                                </div>

                                <div>

                                    <p class="text-sm font-bold text-slate-800">
                                        Service Visibility
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $service->status
                                            ? 'This service is currently active.'
                                            : 'This service is currently inactive.' }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================================
                 FOOTER
            ========================================== --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8">

                <a href="{{ route('admin.services.index') }}" class="btn-premium btn-premium-outline">
                    Cancel
                </a>

                <button type="submit" class="btn-premium btn-premium-primary">
                    <i class="bi bi-check2-circle"></i>
                    Update Service
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

                imageName.textContent = 'New image selected: ' + this.files[0].name;
                imageName.classList.remove('hidden');

            } else {

                imageName.textContent = '';
                imageName.classList.add('hidden');

            }

        });

    }
</script>

@endsection
