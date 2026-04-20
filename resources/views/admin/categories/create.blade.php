@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Add Category</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1 font-medium">Category Name</label>
            <input type="text" name="name"
                   class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
            @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Save
        </button>
    </form>
</div>

@endsection