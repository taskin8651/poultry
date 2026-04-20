@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-xl font-bold">Edit Hero</h1>

        <a href="{{ route('admin.heroes.index') }}"
           class="text-sm text-blue-600 hover:underline">
            ← Back
        </a>
    </div>

    {{-- FORM --}}
    <form method="POST"
          enctype="multipart/form-data"
          action="{{ route('admin.heroes.update', $hero->id) }}">
        @csrf
        @method('PUT')

        {{-- TITLE --}}
        <div class="mb-4">
            <label class="block text-sm mb-1">Title</label>
            <input type="text" name="title"
                   value="{{ old('title', $hero->title) }}"
                   class="w-full border p-2 rounded">

            @error('title')
                <p class="text-red-500 text-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- SUBTITLE --}}
        <div class="mb-4">
            <label class="block text-sm mb-1">Subtitle</label>
            <input type="text" name="subtitle"
                   value="{{ old('subtitle', $hero->subtitle) }}"
                   class="w-full border p-2 rounded">
        </div>

        {{-- BUTTON TEXT --}}
        <div class="mb-4">
            <label class="block text-sm mb-1">Button Text</label>
            <input type="text" name="button_text"
                   value="{{ old('button_text', $hero->button_text) }}"
                   class="w-full border p-2 rounded">
        </div>

        {{-- BUTTON LINK --}}
        <div class="mb-4">
            <label class="block text-sm mb-1">Button Link</label>
            <input type="text" name="button_link"
                   value="{{ old('button_link', $hero->button_link) }}"
                   class="w-full border p-2 rounded">
        </div>

        {{-- IMAGE --}}
        <div class="mb-4">
            <label class="block text-sm mb-1">Current Image</label>

            @if($hero->getFirstMediaUrl('hero_image'))
                <img src="{{ $hero->getFirstMediaUrl('hero_image') }}"
                     class="w-32 h-20 object-cover rounded mb-2">
            @endif

            <input type="file" name="image" class="w-full">
        </div>

        {{-- STATUS --}}
        <div class="mb-4">
            <label class="block text-sm mb-1">Status</label>

            <select name="status" class="w-full border p-2 rounded">
                <option value="1" {{ $hero->status ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$hero->status ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        {{-- BUTTON --}}
        <div class="flex gap-3">
            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Update
            </button>

            <a href="{{ route('admin.heroes.index') }}"
               class="text-gray-600 hover:underline">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection