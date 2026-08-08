@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">

        <div class="page-icon">
            <i class="bi bi-people-fill"></i>
        </div>

        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                    {{ trans('cruds.user.title') }}
                </h1>

                <span class="badge-premium badge-info">
                    {{ $users->count() }} {{ trans('cruds.user.title') }}
                </span>
            </div>

            <p class="mt-1 text-sm text-slate-500">
                Manage application users and their roles
            </p>
        </div>

    </div>

    @can('user_create')
        <a href="{{ route('admin.users.create') }}" class="btn-premium btn-premium-primary">
            <i class="bi bi-plus-lg"></i>
            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </a>
    @endcan
</div>

{{-- TABLE CARD --}}
<div class="card-premium overflow-hidden">
    <div class="table-premium-wrap">
        <table class="min-w-full text-sm datatable datatable-User">
            <thead>
                <tr>
                    <th class="w-10"></th>
                    <th>
                        {{ trans('cruds.user.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.email') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.email_verified_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.user.fields.roles') }}
                    </th>
                    <th class="text-right">
                        {{ trans('global.actions') }}
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $user)
                    <tr data-entry-id="{{ $user->id }}">

                        <td></td>

                        <td class="font-bold text-slate-900">
                            #{{ $user->id }}
                        </td>

                        <td>
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center
                                            rounded-full bg-gradient-to-br from-indigo-600 to-violet-600
                                            text-sm font-bold text-white shadow-md shadow-indigo-200">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <p class="font-bold text-slate-800">
                                    {{ $user->name }}
                                </p>
                            </div>
                        </td>

                        <td class="text-slate-600">
                            {{ $user->email }}
                        </td>

                        <td>
                            @if($user->email_verified_at)
                                <span class="badge-premium badge-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ $user->email_verified_at->format('d M Y') }}
                                </span>
                            @else
                                <span class="badge-premium badge-neutral">
                                    &mdash;
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($user->roles as $role)
                                    <span class="badge-premium badge-info">
                                        {{ $role->title }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">

                                @can('user_show')
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                       class="btn-premium btn-premium-outline !px-3 !py-1.5 !text-xs">
                                        <i class="bi bi-eye"></i>
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('user_edit')
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn-premium btn-premium-amber !px-3 !py-1.5 !text-xs">
                                        <i class="bi bi-pencil-square"></i>
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('user_delete')
                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit"
                                                class="btn-premium btn-premium-danger !px-3 !py-1.5 !text-xs">
                                            <i class="bi bi-trash3"></i>
                                            {{ trans('global.delete') }}
                                        </button>
                                    </form>
                                @endcan

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

    @can('user_delete')
    let deleteButton = {
        text: '{{ trans('global.datatables.delete') }}',
        url: "{{ route('admin.users.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
            let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id')
            });

            if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}');
                return;
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: { 'x-csrf-token': _token },
                    method: 'POST',
                    url: config.url,
                    data: { ids: ids, _method: 'DELETE' }
                }).done(() => location.reload());
            }
        }
    };
    dtButtons.push(deleteButton);
    @endcan

    $('.datatable-User:not(.ajaxTable)').DataTable({
        buttons: dtButtons,
        order: [[1, 'desc']],
        pageLength: 25,
        scrollX: true
    });
});
</script>
@endsection
