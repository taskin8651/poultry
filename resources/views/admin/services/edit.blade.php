@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-xl mx-auto">

<h1 class="text-xl font-bold mb-4">Edit Service</h1>

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.services.update',$service->id) }}">
@csrf
@method('PUT')

<input name="title" value="{{ $service->title }}" class="w-full border p-2 mb-3">

<textarea name="description" class="w-full border p-2 mb-3">
{{ $service->description }}
</textarea>

@if($service->getFirstMediaUrl('service_image'))
<img src="{{ $service->getFirstMediaUrl('service_image') }}" class="w-24 mb-2">
@endif

<input type="file" name="image" class="mb-3">

<select name="status" class="w-full border p-2 mb-3">
    <option value="1" {{ $service->status?'selected':'' }}>Active</option>
    <option value="0" {{ !$service->status?'selected':'' }}>Inactive</option>
</select>

<button class="bg-green-600 text-white px-4 py-2 rounded">
    Update
</button>

</form>

</div>

@endsection