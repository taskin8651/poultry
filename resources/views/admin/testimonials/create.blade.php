@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-xl mx-auto">

<h1 class="text-xl font-bold mb-4">Add Testimonial</h1>

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.testimonials.store') }}">
@csrf

<input name="name" placeholder="Name" class="w-full border p-2 mb-3">

<input name="position" placeholder="Position" class="w-full border p-2 mb-3">

<textarea name="message" placeholder="Message"
          class="w-full border p-2 mb-3"></textarea>

<input name="rating" type="number" min="1" max="5"
       class="w-full border p-2 mb-3">

<input type="file" name="image" class="mb-3">

<select name="status" class="w-full border p-2 mb-3">
    <option value="1">Active</option>
    <option value="0">Inactive</option>
</select>

<button class="bg-blue-600 text-white px-4 py-2 rounded">
    Save
</button>

</form>

</div>

@endsection