@extends('layouts.admin')

@section('content')

<div class="mx-auto w-full">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Contact Messages
                    </h1>

                    <span class="badge-premium badge-info">
                        {{ $contacts->total() }} Messages
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Messages submitted through the contact form
                </p>

            </div>

        </div>

    </div>



    {{-- =====================================================
         CONTACTS TABLE
    ====================================================== --}}
    <div class="card-premium overflow-hidden">

        <div class="table-premium-wrap">

            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($contacts as $c)
                    <tr>

                        <td class="font-bold text-slate-800">{{ $c->name }}</td>

                        <td>{{ $c->email }}</td>

                        <td>{{ $c->phone ?? '-' }}</td>

                        <td class="max-w-xs">
                            <span class="line-clamp-1 text-slate-500">
                                {{ $c->message }}
                            </span>
                        </td>

                        <td>

                            <div class="flex items-center gap-2">

                                <a href="{{ route('admin.contacts.show',$c->id) }}"
                                   class="btn-premium btn-premium-outline !px-3 !py-2 text-xs">
                                    <i class="bi bi-eye-fill"></i>
                                    View
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.contacts.destroy',$c->id) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn-premium btn-premium-danger !px-3 !py-2 text-xs">
                                        <i class="bi bi-trash3"></i>
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-14 text-center text-sm text-slate-400">
                            No contact messages found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

    </div>

    <div class="mt-6">
        {{ $contacts->links() }}
    </div>

</div>

@endsection
