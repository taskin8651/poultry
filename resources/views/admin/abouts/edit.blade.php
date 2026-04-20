@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-xl mx-auto">

<h1 class="text-xl font-bold mb-4">Edit About</h1>

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.abouts.update', $about->id) }}">
@csrf
@method('PUT')

<input name="title" value="{{ $about->title }}" class="w-full border p-2 mb-3">

<input name="subtitle" value="{{ $about->subtitle }}" class="w-full border p-2 mb-3">

<textarea name="description" class="w-full border p-2 mb-3">
{{ $about->description }}
</textarea>

@if($about->getFirstMediaUrl('about_image'))
<img src="{{ $about->getFirstMediaUrl('about_image') }}" class="w-32 mb-2">
@endif

<input type="file" name="image" class="mb-3">

<select name="status" class="w-full border p-2 mb-3">
    <option value="1" {{ $about->status ? 'selected':'' }}>Active</option>
    <option value="0" {{ !$about->status ? 'selected':'' }}>Inactive</option>
</select>

<button class="bg-green-600 text-white px-4 py-2 rounded">
    Update
</button>

</form>

</div>

@endsection