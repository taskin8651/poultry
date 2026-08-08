@extends('layouts.admin')
@section('content')

{{-- HEADER --}}
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-key-fill"></i>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                {{ trans('global.show') }} {{ trans('cruds.permission.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Permission details and configuration
            </p>
        </div>
    </div>

    <a href="{{ route('admin.permissions.index') }}" class="btn-premium btn-premium-outline">
        <i class="bi bi-arrow-left"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- CONTENT --}}
<div class="card-premium max-w-2xl overflow-hidden">

    {{-- BODY --}}
    <div class="p-6 sm:p-8">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            {{-- ID --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.permission.fields.id') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    #{{ $permission->id }}
                </p>
            </div>

            {{-- TITLE --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.permission.fields.title') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $permission->title }}
                </p>
            </div>

        </div>

    </div>

    {{-- FOOTER --}}
    <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-5 sm:px-8">

        <a href="{{ route('admin.permissions.index') }}" class="btn-premium btn-premium-outline">
            {{ trans('global.back_to_list') }}
        </a>

        @can('permission_edit')
            <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn-premium btn-premium-primary">
                <i class="bi bi-pencil-square"></i>
                {{ trans('global.edit') }}
            </a>
        @endcan

    </div>
</div>

@endsection
