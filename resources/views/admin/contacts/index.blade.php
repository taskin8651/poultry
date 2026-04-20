@extends('layouts.admin')

@section('content')

<div class="p-6">

<h1 class="text-xl font-bold mb-4">Contact Messages</h1>

<table class="w-full bg-white shadow">

<thead>
<tr>
<th>Name</th>
<th>Email</th>
<th>Action</th>
</tr>
</thead>

<tbody>
@foreach($contacts as $c)
<tr class="border-t">

<td>{{ $c->name }}</td>
<td>{{ $c->email }}</td>

<td>
<a href="{{ route('admin.contacts.show',$c->id) }}">View</a>

<form method="POST"
      action="{{ route('admin.contacts.destroy',$c->id) }}">
@csrf
@method('DELETE')

<button>Delete</button>
</form>
</td>

</tr>
@endforeach
</tbody>

</table>

</div>

@endsection