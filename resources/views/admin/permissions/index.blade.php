@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-key-fill"></i>
        </div>

        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                    {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
                </h1>

                <span class="badge-premium badge-info">
                    {{ $permissions->count() }} Permissions
                </span>
            </div>

            <p class="mt-1 text-sm text-slate-500">
                Manage system permissions
            </p>
        </div>
    </div>

    @can('permission_create')
        <a href="{{ route('admin.permissions.create') }}" class="btn-premium btn-premium-primary">
            <i class="bi bi-plus-lg"></i>
            {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
        </a>
    @endcan
</div>

{{-- TABLE CARD --}}
<div class="card-premium overflow-hidden">
    <div class="table-premium-wrap">
        <table class="min-w-full text-sm datatable-Permission">

            <thead>
                <tr>
                    <th class="w-8"></th>
                    <th>
                        {{ trans('cruds.permission.fields.id') }}
                    </th>
                    <th>
                        {{ trans('cruds.permission.fields.title') }}
                    </th>
                    <th class="text-center">
                        {{ trans('global.actions') }}
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach($permissions as $permission)
                    <tr data-entry-id="{{ $permission->id }}">

                        <td></td>

                        <td class="font-bold text-slate-900">
                            {{ $permission->id }}
                        </td>

                        <td class="font-semibold text-slate-700">
                            <span class="badge-premium badge-neutral">
                                {{ $permission->title }}
                            </span>
                        </td>

                        <td class="text-center">
                            <div class="flex items-center justify-center gap-2">

                                @can('permission_show')
                                    <a href="{{ route('admin.permissions.show', $permission->id) }}"
                                       class="btn-premium btn-premium-outline !px-3 !py-1.5 !text-xs">
                                        <i class="bi bi-eye"></i>
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('permission_edit')
                                    <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                                       class="btn-premium btn-premium-amber !px-3 !py-1.5 !text-xs">
                                        <i class="bi bi-pencil-square"></i>
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('permission_delete')
                                    <form action="{{ route('admin.permissions.destroy', $permission->id) }}"
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

    @can('permission_delete')
    let deleteButton = {
        text: '{{ trans('global.datatables.delete') }}',
        className: 'btn-danger',
        action: function (e, dt) {
            let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id');
            });

            if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}');
                return;
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: { 'x-csrf-token': $('meta[name="csrf-token"]').attr('content') },
                    method: 'POST',
                    url: "{{ route('admin.permissions.massDestroy') }}",
                    data: { ids: ids, _method: 'DELETE' }
                }).done(function () {
                    location.reload();
                });
            }
        }
    };
    dtButtons.push(deleteButton);
    @endcan

    $('.datatable-Permission').DataTable({
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
