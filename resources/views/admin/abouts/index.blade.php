@extends('layouts.admin')

@section('content')

<div class="p-6">

<h1 class="text-xl font-bold mb-4">About Section</h1>

<a href="{{ route('admin.abouts.create') }}"
   class="bg-blue-600 text-white px-3 py-2 rounded">
   Add
</a>

<table class="w-full mt-4">

@foreach($abouts as $about)
<tr class="border-t">

<td>{{ $about->title }}</td>

<td>
<img src="{{ $about->getFirstMediaUrl('about_image') }}" class="w-20">
</td>

<td>{{ $about->status ? 'Active':'Inactive' }}</td>

<td>
<a href="{{ route('admin.abouts.edit',$about->id) }}">Edit</a>
</td>

</tr>
@endforeach

</table>

</div>

@endsection