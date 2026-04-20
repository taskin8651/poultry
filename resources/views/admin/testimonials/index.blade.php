@extends('layouts.admin')

@section('content')

<div class="p-6">

<h1 class="text-xl font-bold mb-4">Testimonials</h1>

<a href="{{ route('admin.testimonials.create') }}"
   class="bg-blue-600 text-white px-3 py-2 rounded">
   Add
</a>

<table class="w-full mt-4">

@foreach($testimonials as $t)
<tr class="border-t">

<td>{{ $t->name }}</td>
<td>{{ $t->rating }} ⭐</td>

<td>
<img src="{{ $t->getFirstMediaUrl('testimonial_image') }}" class="w-16">
</td>

<td>{{ $t->status ? 'Active':'Inactive' }}</td>

<td>
<a href="{{ route('admin.testimonials.edit',$t->id) }}">Edit</a>
</td>

</tr>
@endforeach

</table>

</div>

@endsection