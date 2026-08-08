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
                {{ trans('global.edit') }} {{ trans('cruds.permission.title_singular') }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Update existing permission details
            </p>
        </div>
    </div>

    <a href="{{ route('admin.permissions.index') }}" class="btn-premium btn-premium-outline">
        <i class="bi bi-arrow-left"></i>
        {{ trans('global.back_to_list') }}
    </a>
</div>

{{-- FORM CARD --}}
<div class="card-premium max-w-xl overflow-hidden">

    <form method="POST" action="{{ route('admin.permissions.update', $permission->id) }}">
        @method('PUT')
        @csrf

        <div class="p-6 sm:p-8">

            {{-- TITLE --}}
            <div>
                <label for="title" class="label-premium">
                    {{ trans('cruds.permission.fields.title') }}
                    <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       name="title"
                       id="title"
                       value="{{ old('title', $permission->title) }}"
                       required
                       class="input-premium">

                @if($errors->has('title'))
                    <p class="mt-1 text-xs font-medium text-red-500">
                        {{ $errors->first('title') }}
                    </p>
                @endif

                @if(trans('cruds.permission.fields.title_helper'))
                    <p class="mt-1 text-xs text-slate-400">
                        {{ trans('cruds.permission.fields.title_helper') }}
                    </p>
                @endif
            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-col-reverse gap-3 border-t border-slate-100
                    bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8">

            <a href="{{ route('admin.permissions.index') }}" class="btn-premium btn-premium-outline">
                {{ trans('global.cancel') }}
            </a>

            <button type="submit" class="btn-premium btn-premium-primary">
                <i class="bi bi-check2-circle"></i>
                {{ trans('global.save') }}
            </button>

        </div>

    </form>
</div>

@endsection
