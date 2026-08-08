@extends('layouts.admin')
@section('content')

@php
    $knownActions = ['create', 'edit', 'show', 'delete', 'access'];
    $actionIcons = [
        'create' => 'bi-plus-circle',
        'edit'   => 'bi-pencil-square',
        'show'   => 'bi-eye',
        'delete' => 'bi-trash3',
        'access' => 'bi-door-open',
    ];

    $groupedPermissions = [];

    foreach ($permissions as $id => $title) {
        $parts = explode('_', $title);
        $action = end($parts);

        if (count($parts) > 1 && in_array($action, $knownActions)) {
            array_pop($parts);
            $group = implode('_', $parts);
        } else {
            $group = $title;
            $action = null;
        }

        $groupLabel = ucwords(str_replace('_', ' ', $group));
        $groupedPermissions[$groupLabel][] = [
            'id'     => $id,
            'title'  => $title,
            'action' => $action,
        ];
    }

    ksort($groupedPermissions);
@endphp

{{-- PAGE HEADER --}}
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-pencil-square"></i>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                {{ trans('global.edit') }} {{ trans('cruds.role.title_singular') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Update role details and assigned permissions
            </p>
        </div>
    </div>

    <a href="{{ route('admin.roles.index') }}" class="btn-premium btn-premium-outline">
        <i class="bi bi-arrow-left"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

<form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
@method('PUT')
@csrf

<div class="card-premium overflow-hidden">

    {{-- =========================================
         ROLE INFORMATION
    ========================================== --}}
    <div class="border-b border-slate-100 p-6 sm:p-8">

        <div class="mb-6 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-tag"></i>
            </div>
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">
                    Role Information
                </h2>
                <p class="text-sm text-slate-500">
                    Update this role's name.
                </p>
            </div>
        </div>

        <div class="max-w-md">
            <label for="title" class="label-premium">
                {{ trans('cruds.role.fields.title') }}
                <span class="text-red-500">*</span>
            </label>

            <input type="text"
                   name="title"
                   id="title"
                   value="{{ old('title', $role->title) }}"
                   required
                   class="input-premium">

            @if($errors->has('title'))
                <p class="mt-1 text-xs font-medium text-red-500">
                    {{ $errors->first('title') }}
                </p>
            @endif

            <p class="mt-1 text-xs text-slate-400">
                {{ trans('cruds.role.fields.title_helper') }}
            </p>
        </div>

    </div>

    {{-- =========================================
         PERMISSIONS
    ========================================== --}}
    <div class="p-6 sm:p-8">

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">
                        {{ trans('cruds.role.fields.permissions') }}
                    </h2>
                    <p class="text-sm text-slate-500">
                        Choose what this role is allowed to do.
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="button" id="select-all" class="btn-premium btn-premium-outline !px-3 !py-1.5 !text-xs">
                    {{ trans('global.select_all') }}
                </button>

                <button type="button" id="deselect-all" class="btn-premium btn-premium-outline !px-3 !py-1.5 !text-xs">
                    {{ trans('global.deselect_all') }}
                </button>
            </div>
        </div>

        <div class="max-h-[420px] space-y-4 overflow-y-auto pr-1">

            @foreach($groupedPermissions as $groupLabel => $perms)
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">

                    <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">
                        {{ $groupLabel }}
                    </p>

                    <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-3">

                        @foreach($perms as $perm)
                            <label class="flex items-center gap-2.5 rounded-xl border border-slate-200
                                          bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700
                                          transition hover:border-indigo-200 hover:bg-indigo-50/40 cursor-pointer">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="{{ $perm['id'] }}"
                                       class="permission-checkbox h-4 w-4 rounded border-slate-300
                                              text-indigo-600 focus:ring-indigo-500"
                                       {{ (in_array($perm['id'], old('permissions', [])) || $role->permissions->contains($perm['id'])) ? 'checked' : '' }}>

                                @if($perm['action'] && isset($actionIcons[$perm['action']]))
                                    <i class="bi {{ $actionIcons[$perm['action']] }} text-indigo-500"></i>
                                @endif

                                <span>{{ $perm['title'] }}</span>
                            </label>
                        @endforeach

                    </div>

                </div>
            @endforeach

        </div>

        @if($errors->has('permissions'))
            <p class="mt-3 text-xs font-medium text-red-500">
                {{ $errors->first('permissions') }}
            </p>
        @endif

        <p class="mt-3 text-xs text-slate-400">
            {{ trans('cruds.role.fields.permissions_helper') }}
        </p>

    </div>

    {{-- =========================================
         FOOTER
    ========================================== --}}
    <div class="flex flex-col-reverse gap-3 border-t border-slate-100
                bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8">

        <a href="{{ route('admin.roles.index') }}" class="btn-premium btn-premium-outline">
            {{ trans('global.cancel') }}
        </a>

        <button type="submit" class="btn-premium btn-premium-primary">
            <i class="bi bi-check2-circle"></i>
            {{ trans('global.save') }}
        </button>

    </div>

</div>

</form>

@endsection

@section('scripts')
@parent
<script>
document.getElementById('select-all').addEventListener('click', function () {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
});

document.getElementById('deselect-all').addEventListener('click', function () {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
});
</script>
@endsection
