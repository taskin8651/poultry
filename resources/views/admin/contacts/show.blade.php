@extends('layouts.admin')

@section('content')

<div class="p-6">

<h1 class="text-xl font-bold mb-4">Message Detail</h1>

<p><strong>Name:</strong> {{ $contact->name }}</p>
<p><strong>Email:</strong> {{ $contact->email }}</p>
<p><strong>Phone:</strong> {{ $contact->phone }}</p>
<p><strong>Message:</strong> {{ $contact->message }}</p>

</div>

@endsection