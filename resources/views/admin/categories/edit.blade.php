@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Category</h1>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 font-medium">Category Name</label>
            <input type="text" name="name"
                   value="{{ $category->name }}"
                   class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">

            @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Update
        </button>
    </form>
</div>

@endsection