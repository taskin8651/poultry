@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-shield-check"></i>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                {{ trans('global.show') }} {{ trans('cruds.role.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                View role details and assigned permissions
            </p>
        </div>
    </div>

    <a href="{{ route('admin.roles.index') }}" class="btn-premium btn-premium-outline">
        <i class="bi bi-arrow-left"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- CONTENT CARD --}}
<div class="card-premium max-w-3xl overflow-hidden">

    <div class="p-6 sm:p-8">

        {{-- BASIC INFO --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            {{-- ROLE ID --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.role.fields.id') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    #{{ $role->id }}
                </p>
            </div>

            {{-- ROLE TITLE --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.role.fields.title') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $role->title }}
                </p>
            </div>

        </div>

        {{-- PERMISSIONS --}}
        <div class="mt-6 rounded-2xl border border-slate-100 bg-slate-50 p-4">
            <p class="mb-3 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                {{ trans('cruds.role.fields.permissions') }}
            </p>

            @if($role->permissions->count())
                <div class="flex flex-wrap gap-2">
                    @foreach($role->permissions as $permission)
                        <span class="badge-premium badge-info">
                            {{ $permission->title }}
                        </span>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-500">
                    No permissions assigned
                </p>
            @endif
        </div>

    </div>

    {{-- FOOTER ACTIONS --}}
    <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-5 sm:px-8">

        <a href="{{ route('admin.roles.index') }}" class="btn-premium btn-premium-outline">
            {{ trans('global.back_to_list') }}
        </a>

        @can('role_edit')
            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn-premium btn-premium-primary">
                <i class="bi bi-pencil-square"></i>
                {{ trans('global.edit') }}
            </a>
        @endcan

    </div>
</div>

@endsection
