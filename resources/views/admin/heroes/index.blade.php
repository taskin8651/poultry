@extends('layouts.admin')

@section('content')

<div class="p-6">

<h1 class="text-xl font-bold mb-4">Hero Slider</h1>

<a href="{{ route('admin.heroes.create') }}"
   class="bg-blue-600 text-white px-3 py-2 rounded">Add</a>

<table class="w-full mt-4">

@foreach($heroes as $hero)
<tr class="border-t">

<td>{{ $hero->title }}</td>

<td>
<img src="{{ $hero->getFirstMediaUrl('hero_image') }}"
     class="w-20">
</td>

<td>{{ $hero->status ? 'Active' : 'Inactive' }}</td>

<td>
<a href="{{ route('admin.heroes.edit',$hero->id) }}">Edit</a>
</td>

</tr>
@endforeach

</table>

</div>

@endsection