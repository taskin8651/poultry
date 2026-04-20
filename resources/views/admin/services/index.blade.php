@extends('layouts.admin')

@section('content')

<div class="p-6">

<h1 class="text-xl font-bold mb-4">Services</h1>

<a href="{{ route('admin.services.create') }}"
   class="bg-blue-600 text-white px-3 py-2 rounded">
   Add
</a>

<table class="w-full mt-4">

@foreach($services as $service)
<tr class="border-t">

<td>{{ $service->title }}</td>

<td>
<img src="{{ $service->getFirstMediaUrl('service_image') }}" class="w-20">
</td>

<td>{{ $service->status?'Active':'Inactive' }}</td>

<td>
<a href="{{ route('admin.services.edit',$service->id) }}">Edit</a>
</td>

</tr>
@endforeach

</table>

</div>

@endsection