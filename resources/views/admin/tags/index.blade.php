@extends('layouts.admin')

@section('content')

<div class="mx-auto w-full">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-tags-fill"></i>
            </div>

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Tags
                    </h1>

                    <span class="badge-premium badge-info">
                        {{ $tags->total() }} Tags
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Manage tags used to label your products
                </p>

            </div>

        </div>

        <a href="{{ route('admin.tags.create') }}" class="btn-premium btn-premium-primary">
            <i class="bi bi-plus-lg"></i>
            Add Tag
        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}
    @if(session('success'))

        <div class="alert-premium-success mb-6">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- =====================================================
         TAGS TABLE
    ====================================================== --}}
    @if($tags->count())

        <div class="card-premium overflow-hidden">

            <div class="table-premium-wrap">

                <table class="table-premium">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tags as $tag)
                        <tr>
                            <td class="text-slate-400">#{{ $tag->id }}</td>
                            <td class="font-bold text-slate-800">{{ $tag->name }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('admin.tags.edit', $tag->id) }}"
                                       class="btn-premium btn-premium-amber !px-3 !py-2 !text-xs">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.tags.destroy', $tag->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this tag?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn-premium btn-premium-danger !px-3 !py-2 !text-xs">
                                            <i class="bi bi-trash3"></i>
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>

        <div class="mt-6">
            {{ $tags->links() }}
        </div>

    @else

        {{-- =====================================================
             EMPTY STATE
        ====================================================== --}}
        <div class="card-premium px-6 py-20 text-center">

            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-tag text-4xl"></i>
            </div>

            <h2 class="mt-6 text-xl font-extrabold text-slate-900">
                No Tags Yet
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                Create your first tag to start labelling your products.
            </p>

            <a href="{{ route('admin.tags.create') }}" class="btn-premium btn-premium-primary mt-6">
                <i class="bi bi-plus-lg"></i>
                Add First Tag
            </a>

        </div>

    @endif

</div>

@endsection
