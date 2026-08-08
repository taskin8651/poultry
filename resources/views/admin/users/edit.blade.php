@extends('layouts.admin')
@section('content')

{{-- PAGE HEADER --}}
<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div class="flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-pencil-square"></i>
        </div>

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">
                {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Update user information and assigned roles
            </p>
        </div>
    </div>

    <a href="{{ route('admin.users.index') }}" class="btn-premium btn-premium-outline">
        <i class="bi bi-arrow-left"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
@method('PUT')
@csrf

<div class="card-premium overflow-hidden">

    {{-- =========================================
         USER INFORMATION
    ========================================== --}}
    <div class="border-b border-slate-100 p-6 sm:p-8">

        <div class="mb-6 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-person-vcard"></i>
            </div>
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">
                    User Information
                </h2>
                <p class="text-sm text-slate-500">
                    Update the account details for this user.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

            {{-- NAME --}}
            <div>
                <label for="name" class="label-premium">
                    {{ trans('cruds.user.fields.name') }}
                    <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       name="name"
                       id="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       class="input-premium">

                @if($errors->has('name'))
                    <p class="mt-1 text-xs font-medium text-red-500">
                        {{ $errors->first('name') }}
                    </p>
                @endif
            </div>

            {{-- EMAIL --}}
            <div>
                <label for="email" class="label-premium">
                    {{ trans('cruds.user.fields.email') }}
                    <span class="text-red-500">*</span>
                </label>

                <input type="email"
                       name="email"
                       id="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       class="input-premium">

                @if($errors->has('email'))
                    <p class="mt-1 text-xs font-medium text-red-500">
                        {{ $errors->first('email') }}
                    </p>
                @endif
            </div>

            {{-- PASSWORD (OPTIONAL) --}}
            <div class="sm:col-span-2">
                <label for="password" class="label-premium">
                    {{ trans('cruds.user.fields.password') }}
                </label>

                <input type="password"
                       name="password"
                       id="password"
                       placeholder="Leave blank to keep current password"
                       class="input-premium">

                @if($errors->has('password'))
                    <p class="mt-1 text-xs font-medium text-red-500">
                        {{ $errors->first('password') }}
                    </p>
                @endif

                <p class="mt-1 text-xs text-slate-400">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>

        </div>

    </div>

    {{-- =========================================
         ROLES
    ========================================== --}}
    <div class="p-6 sm:p-8">

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">
                        {{ trans('cruds.user.fields.roles') }}
                    </h2>
                    <p class="text-sm text-slate-500">
                        Choose which roles this user should have.
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

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 max-h-[300px] overflow-y-auto pr-1">

            @foreach($roles as $id => $role)
                <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3.5
                               transition hover:border-indigo-200 hover:bg-indigo-50/40 cursor-pointer">
                    <input type="checkbox"
                           name="roles[]"
                           value="{{ $id }}"
                           class="role-checkbox mt-0.5 h-4 w-4 rounded border-slate-300
                                  text-indigo-600 focus:ring-indigo-500"
                           {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'checked' : '' }}>

                    <span class="text-sm font-semibold text-slate-700">
                        {{ $role }}
                    </span>
                </label>
            @endforeach

        </div>

        @if($errors->has('roles'))
            <p class="mt-3 text-xs font-medium text-red-500">
                {{ $errors->first('roles') }}
            </p>
        @endif

        <p class="mt-3 text-xs text-slate-400">
            {{ trans('cruds.user.fields.roles_helper') }}
        </p>

    </div>

    {{-- =========================================
         FOOTER
    ========================================== --}}
    <div class="flex flex-col-reverse gap-3 border-t border-slate-100
                bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8">

        <a href="{{ route('admin.users.index') }}" class="btn-premium btn-premium-outline">
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
    document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = true);
});

document.getElementById('deselect-all').addEventListener('click', function () {
    document.querySelectorAll('.role-checkbox').forEach(cb => cb.checked = false);
});
</script>
@endsection
