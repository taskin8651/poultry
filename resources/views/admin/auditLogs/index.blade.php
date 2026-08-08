@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-clock-history"></i>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Track and monitor system activities
            </p>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="relative">
        <input type="text"
               id="globalSearch"
               placeholder="Search logs..."
               class="input-premium w-64 !pl-10">
        <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
    </div>
</div>

{{-- STATS --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

    <div class="card-premium flex items-center gap-4 p-5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
            <i class="bi bi-list-ul"></i>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Logs</p>
            <p class="text-xl font-extrabold text-slate-900">{{ $auditLogs->total() }}</p>
        </div>
    </div>

    <div class="card-premium flex items-center gap-4 p-5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Today</p>
            <p class="text-xl font-extrabold text-slate-900">
                {{ $auditLogs->where('created_at', '>=', today())->count() }}
            </p>
        </div>
    </div>

    <div class="card-premium flex items-center gap-4 p-5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
            <i class="bi bi-people"></i>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Users</p>
            <p class="text-xl font-extrabold text-slate-900">
                {{ $auditLogs->pluck('user_id')->unique()->count() }}
            </p>
        </div>
    </div>

    <div class="card-premium flex items-center gap-4 p-5">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
            <i class="bi bi-boxes"></i>
        </div>
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Models</p>
            <p class="text-xl font-extrabold text-slate-900">
                {{ $auditLogs->pluck('subject_type')->unique()->count() }}
            </p>
        </div>
    </div>

</div>

{{-- TABLE CARD --}}
<div class="card-premium overflow-hidden">

    <div class="table-premium-wrap">
        <table class="min-w-full text-sm datatable-AuditLog">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Action</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>User</th>
                    <th>IP</th>
                    <th>Time</th>
                    <th class="text-center">View</th>
                </tr>
            </thead>

            <tbody>
                @foreach($auditLogs as $auditLog)
                    <tr>

                        <td class="font-bold text-slate-900">
                            {{ $auditLog->id }}
                        </td>

                        <td class="font-semibold text-slate-700">
                            {{ ucfirst($auditLog->description) }}
                        </td>

                        <td class="text-slate-600">
                            {{ $auditLog->subject_id }}
                        </td>

                        <td>
                            <span class="badge-premium badge-neutral">
                                {{ class_basename($auditLog->subject_type) }}
                            </span>
                        </td>

                        <td class="text-slate-700">
                            {{ $auditLog->user?->name ?? 'System' }}
                        </td>

                        <td class="text-xs text-slate-500">
                            {{ $auditLog->host }}
                        </td>

                        <td class="text-xs text-slate-400">
                            {{ $auditLog->created_at->format('d M Y, H:i') }}
                        </td>

                        <td class="text-center">
                            @can('audit_log_show')
                                <a href="{{ route('admin.audit-logs.show', $auditLog->id) }}"
                                   class="btn-premium btn-premium-outline !px-3 !py-1.5 !text-xs">
                                    <i class="bi bi-eye"></i>
                                    View
                                </a>
                            @endcan
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    {{-- PAGINATION (SAFE & ERROR FREE) --}}
    <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/70 px-6 py-4 text-sm">
        <div class="text-slate-500">
            Showing {{ $auditLogs->firstItem() }} to {{ $auditLogs->lastItem() }}
            of {{ $auditLogs->total() }} entries
        </div>

        <div>
            {{ $auditLogs->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {

    let table = $('.datatable-AuditLog').DataTable({
        paging: false, // Laravel pagination use ho rahi hai
        searching: true,
        ordering: true,
        order: [[0, 'desc']],
        info: false
    });

    $('#globalSearch').on('keyup', function () {
        table.search(this.value).draw();
    });

});
</script>
@endsection
