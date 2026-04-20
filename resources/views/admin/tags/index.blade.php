@extends('layouts.admin')

@section('content')

<div class="p-6">
    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">Tags</h1>

        <a href="{{ route('admin.tags.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            + Add Tag
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($tags as $tag)
                <tr class="border-t">
                    <td class="p-3">{{ $tag->id }}</td>
                    <td class="p-3">{{ $tag->name }}</td>
                    <td class="p-3 flex gap-2">

                        <a href="{{ route('admin.tags.edit', $tag->id) }}"
                           class="bg-yellow-500 text-white px-3 py-1 rounded">
                           Edit
                        </a>

                        <form action="{{ route('admin.tags.destroy', $tag->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-600 text-white px-3 py-1 rounded">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tags->links() }}
    </div>
</div>

@endsection