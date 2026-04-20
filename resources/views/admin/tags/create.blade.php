@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Add Tag</h1>

    <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-4">
        @csrf

        <input type="text" name="name"
               placeholder="Tag Name"
               class="w-full border rounded-lg p-2">

        @error('name')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            Save
        </button>
    </form>
</div>

@endsection