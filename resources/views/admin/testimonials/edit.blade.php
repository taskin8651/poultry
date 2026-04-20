@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-xl mx-auto">

    <h1 class="text-xl font-bold mb-4">Edit Testimonial</h1>

    <form method="POST" enctype="multipart/form-data"
          action="{{ route('admin.testimonials.update', $testimonial->id) }}">
        @csrf
        @method('PUT')

        {{-- NAME --}}
        <input name="name"
               value="{{ old('name', $testimonial->name) }}"
               placeholder="Name"
               class="w-full border p-2 mb-3">

        {{-- POSITION --}}
        <input name="position"
               value="{{ old('position', $testimonial->position) }}"
               placeholder="Position"
               class="w-full border p-2 mb-3">

        {{-- MESSAGE --}}
        <textarea name="message"
                  class="w-full border p-2 mb-3"
                  placeholder="Message">{{ old('message', $testimonial->message) }}</textarea>

        {{-- RATING --}}
        <input type="number" name="rating" min="1" max="5"
               value="{{ old('rating', $testimonial->rating) }}"
               class="w-full border p-2 mb-3">

        {{-- IMAGE --}}
        @if($testimonial->getFirstMediaUrl('testimonial_image'))
            <img src="{{ $testimonial->getFirstMediaUrl('testimonial_image') }}"
                 class="w-24 mb-2">
        @endif

        <input type="file" name="image" class="mb-3">

        {{-- STATUS --}}
        <select name="status" class="w-full border p-2 mb-3">
            <option value="1" {{ $testimonial->status ? 'selected':'' }}>Active</option>
            <option value="0" {{ !$testimonial->status ? 'selected':'' }}>Inactive</option>
        </select>

        <button class="bg-green-600 text-white px-4 py-2 rounded">
            Update
        </button>

    </form>

</div>

@endsection