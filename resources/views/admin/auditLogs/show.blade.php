@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-file-earmark-text-fill"></i>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                {{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Full details of this audit log entry
            </p>
        </div>
    </div>

    <a href="{{ route('admin.audit-logs.index') }}" class="btn-premium btn-premium-outline">
        <i class="bi bi-arrow-left"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- CARD --}}
<div class="card-premium overflow-hidden">

    <div class="p-6 sm:p-8">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

            {{-- ID --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.id') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    #{{ $auditLog->id }}
                </p>
            </div>

            {{-- DESCRIPTION --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.description') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $auditLog->description }}
                </p>
            </div>

            {{-- SUBJECT ID --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.subject_id') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $auditLog->subject_id }}
                </p>
            </div>

            {{-- SUBJECT TYPE --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.subject_type') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    <span class="badge-premium badge-neutral">
                        {{ $auditLog->subject_type }}
                    </span>
                </p>
            </div>

            {{-- USER --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.user_id') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $auditLog->user?->name ?? 'System' }}
                    <span class="ml-1 text-xs font-medium text-slate-400">
                        (ID: {{ $auditLog->user_id }})
                    </span>
                </p>
            </div>

            {{-- HOST --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.host') }}
                </p>
                <p class="mt-1 inline-block rounded-lg bg-white px-2.5 py-1 font-mono text-xs font-semibold text-slate-700 shadow-sm">
                    {{ $auditLog->host }}
                </p>
            </div>

            {{-- CREATED AT --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 sm:col-span-2">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.created_at') }}
                </p>
                <p class="mt-1 text-sm font-extrabold text-slate-800">
                    {{ $auditLog->created_at->format('d M Y, H:i:s') }}
                </p>
            </div>

            {{-- PROPERTIES --}}
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 sm:col-span-2">
                <p class="mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                    {{ trans('cruds.auditLog.fields.properties') }}
                </p>
                <pre class="overflow-x-auto rounded-xl border border-slate-100 bg-white p-4 text-xs text-slate-700">{{ json_encode($auditLog->properties, JSON_PRETTY_PRINT) }}</pre>
            </div>

        </div>

    </div>

    {{-- FOOTER --}}
    <div class="flex justify-end border-t border-slate-100 bg-slate-50/70 px-6 py-5 sm:px-8">
        <a href="{{ route('admin.audit-logs.index') }}" class="btn-premium btn-premium-outline">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>

@endsection
