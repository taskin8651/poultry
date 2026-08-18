@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>

        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                    {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
                </h1>

                <span class="badge-premium badge-info">
                    {{ $roles->count() }} Roles
                </span>
            </div>

            <p class="mt-1 text-sm text-slate-500">
                Manage roles and assigned permissions
            </p>
        </div>
    </div>

    @can('role_create')
        <a href="{{ route('admin.roles.create') }}" class="btn-premium btn-premium-primary">
            <i class="bi bi-plus-lg"></i>
            {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
        </a>
    @endcan
</div>

{{-- TABLE CARD --}}
<div class="card-premium overflow-hidden">
    <div class="table-premium-wrap">
        <table class="min-w-full text-sm datatable-Role">

            <thead>
                <tr>
                    <th class="w-8"></th>
                    <th>
                        {{ trans('cruds.role.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.role.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.role.fields.permissions') }}
                    </th>
                    <th class="text-center">
                        {{ trans('global.actions') }}
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach($roles as $role)
                    <tr data-entry-id="{{ $role->id }}">

                        <td></td>

                        <td class="font-bold text-slate-900">
                            {{ $role->id }}
                        </td>

                        <td class="font-bold text-slate-800">
                            {{ $role->title }}
                        </td>

                        {{-- PERMISSIONS --}}
                        <td>
                            <div class="flex max-w-xl flex-wrap gap-1.5">
                                @foreach($role->permissions as $permission)
                                    <span class="badge-premium badge-info">
                                        {{ $permission->title }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">

                                @can('role_show')
                                    <a href="{{ route('admin.roles.show', $role->id) }}"
                                       class="btn-premium btn-premium-outline !px-3 !py-1.5 !text-xs">
                                        <i class="bi bi-eye"></i>
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('role_edit')
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                       class="btn-premium btn-premium-amber !px-3 !py-1.5 !text-xs">
                                        <i class="bi bi-pencil-square"></i>
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('role_delete')
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}"
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

    @can('role_delete')
    let deleteButton = {
        text: '{{ trans('global.datatables.delete') }}',
        className: 'btn-danger',
        action: function (e, dt) {
            let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id');
            });

            if (ids.length === 0) {
                showToast('{{ trans('global.datatables.zero_selected') }}', 'warning');
                return;
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: {
                        'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    method: 'POST',
                    url: "{{ route('admin.roles.massDestroy') }}",
                    data: { ids: ids, _method: 'DELETE' }
                }).done(function () {
                    location.reload();
                });
            }
        }
    };
    dtButtons.push(deleteButton);
    @endcan

    $('.datatable-Role').DataTable({
        buttons: dtButtons,
        order: [[1, 'desc']],
        pageLength: 25,
        scrollX: true,
        select: {
            style: 'multi+shift',
            selector: 'td:first-child'
        }
    });

});
</script>
@endsection
