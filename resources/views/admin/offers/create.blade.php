@extends('layouts.admin')

@section('content')

{{-- Tailwind CSS --}}
<script src="https://cdn.tailwindcss.com"></script>

{{-- Bootstrap Icons --}}
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
>

<style>
    .form-card {
        box-shadow: 0 15px 45px rgba(15, 23, 42, .07);
    }

    .input-premium {
        transition: all .2s ease;
    }

    .input-premium:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, .10);
        outline: none;
    }

    .upload-box {
        transition: all .25s ease;
    }

    .upload-box:hover {
        border-color: #6366f1;
        background: #f8faff;
    }
</style>


<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">

        {{-- =========================================
             HEADER
        ========================================== --}}
        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-4">

                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                           rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600
                           text-white shadow-lg shadow-indigo-200"
                >
                    <i class="bi bi-gift-fill text-xl"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900">
                        Add Offer
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Create a new promotional offer
                    </p>
                </div>

            </div>


            <a
                href="{{ route('admin.offers.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       border border-slate-200 bg-white px-4 py-2.5
                       text-sm font-bold text-slate-600 shadow-sm
                       transition hover:bg-slate-50"
            >
                <i class="bi bi-arrow-left"></i>
                Back to Offers
            </a>

        </div>


        {{-- =========================================
             VALIDATION ERRORS
        ========================================== --}}
        @if($errors->any())

            <div
                class="mb-6 rounded-2xl border border-red-100
                       bg-red-50 p-4 text-red-700"
            >

                <div class="flex items-center gap-2 font-bold">

                    <i class="bi bi-exclamation-triangle-fill"></i>

                    Please fix the following errors:

                </div>

                <ul class="mt-2 list-inside list-disc text-sm">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================
             FORM CARD
        ========================================== --}}
        <div
            class="form-card overflow-hidden rounded-3xl
                   border border-slate-200 bg-white"
        >

            <form
                method="POST"
                enctype="multipart/form-data"
                action="{{ route('admin.offers.store') }}"
            >

                @csrf


                {{-- =========================================
                     BASIC INFORMATION
                ========================================== --}}
                <div class="border-b border-slate-100 p-6 sm:p-8">

                    <div class="mb-6">

                        <h2 class="text-lg font-extrabold text-slate-900">
                            Basic Information
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Enter the main details of your offer.
                        </p>

                    </div>


                    {{-- Title --}}
                    <div class="mb-5">

                        <label
                            for="title"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Offer Title
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="e.g. Buy More, Save More"
                            required
                            class="input-premium w-full rounded-xl border border-slate-200
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   placeholder:text-slate-400"
                        >

                        @error('title')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div>

                        <label
                            for="description"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Write a short description about this offer..."
                            class="input-premium w-full resize-none rounded-xl
                                   border border-slate-200 bg-white px-4 py-3
                                   text-sm text-slate-800
                                   placeholder:text-slate-400"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs font-medium text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- =========================================
                     OFFER CONDITION
                ========================================== --}}
                <div class="border-b border-slate-100 p-6 sm:p-8">

                    <div class="mb-6">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-xl bg-indigo-50 text-indigo-600"
                            >
                                <i class="bi bi-bullseye"></i>
                            </div>

                            <div>

                                <h2 class="text-lg font-extrabold text-slate-900">
                                    Offer Condition
                                </h2>

                                <p class="text-sm text-slate-500">
                                    Define when the customer qualifies for this offer.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="mb-5">

                        <label class="mb-2 block text-sm font-bold text-slate-700">
                            Applies To
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="grid grid-cols-3 gap-3">
                            @foreach(['all' => ['All Products', 'bi-grid'], 'egg' => ['Eggs Only', 'bi-egg-fill'], 'hen' => ['Hens Only', 'bi-feather']] as $value => [$label, $icon])
                                <label class="input-premium flex cursor-pointer flex-col items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-center has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="applies_to" value="{{ $value }}" class="hidden"
                                           {{ old('applies_to', 'all') == $value ? 'checked' : '' }}>
                                    <i class="bi {{ $icon }} text-lg text-indigo-600"></i>
                                    <span class="text-xs font-bold text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        @error('applies_to')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror

                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        {{-- Condition Type --}}
                        <div>

                            <label
                                for="condition_type"
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                Condition Type
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="condition_type"
                                name="condition_type"
                                required
                                class="input-premium w-full rounded-xl border border-slate-200
                                       bg-white px-4 py-3 text-sm text-slate-800"
                            >

                                <option value="">
                                    Select condition
                                </option>

                                <option
                                    value="price"
                                    {{ old('condition_type') == 'price' ? 'selected' : '' }}
                                >
                                    Price / Amount
                                </option>

                                <option
                                    value="kg"
                                    {{ old('condition_type') == 'kg' ? 'selected' : '' }}
                                >
                                    Kilogram (KG)
                                </option>

                                <option
                                    value="piece"
                                    {{ old('condition_type') == 'piece' ? 'selected' : '' }}
                                >
                                    Pieces
                                </option>

                                <option
                                    value="qty"
                                    {{ old('condition_type') == 'qty' ? 'selected' : '' }}
                                >
                                    Quantity
                                </option>

                            </select>

                            @error('condition_type')
                                <p class="mt-1 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Condition Value --}}
                        <div>

                            <label
                                for="condition_value"
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                Condition Value
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="condition_value"
                                type="number"
                                step="0.01"
                                min="0"
                                name="condition_value"
                                value="{{ old('condition_value') }}"
                                placeholder="e.g. 1000"
                                required
                                class="input-premium w-full rounded-xl border border-slate-200
                                       bg-white px-4 py-3 text-sm text-slate-800
                                       placeholder:text-slate-400"
                            >

                            @error('condition_value')
                                <p class="mt-1 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =========================================
                     REWARD (CASHBACK TO WALLET)
                ========================================== --}}
                <div class="border-b border-slate-100 p-6 sm:p-8">

                    <div class="mb-6">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-xl bg-violet-50 text-violet-600"
                            >
                                <i class="bi bi-wallet2"></i>
                            </div>

                            <div>

                                <h2 class="text-lg font-extrabold text-slate-900">
                                    Cashback Reward
                                </h2>

                                <p class="text-sm text-slate-500">
                                    Credited straight to the customer's wallet the moment they qualify.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        {{-- Reward Kind --}}
                        <div>

                            <label
                                for="reward_kind"
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                Cashback Type
                                <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="reward_kind"
                                name="reward_kind"
                                required
                                class="input-premium w-full rounded-xl border border-slate-200
                                       bg-white px-4 py-3 text-sm text-slate-800"
                            >
                                <option value="fixed" {{ old('reward_kind', 'fixed') == 'fixed' ? 'selected' : '' }}>
                                    Fixed Amount (₹)
                                </option>
                                <option value="percent" {{ old('reward_kind') == 'percent' ? 'selected' : '' }}>
                                    Percentage of Purchase (%)
                                </option>
                            </select>

                            @error('reward_kind')
                                <p class="mt-1 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Reward Value --}}
                        <div>

                            <label
                                for="reward_value"
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                Cashback Value
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="reward_value"
                                type="number"
                                step="0.01"
                                min="0"
                                name="reward_value"
                                value="{{ old('reward_value') }}"
                                placeholder="e.g. 10"
                                required
                                class="input-premium w-full rounded-xl border border-slate-200
                                       bg-white px-4 py-3 text-sm text-slate-800
                                       placeholder:text-slate-400"
                            >

                            @error('reward_value')
                                <p class="mt-1 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =========================================
                     VALIDITY
                ========================================== --}}
                <div class="border-b border-slate-100 p-6 sm:p-8">

                    <div class="mb-6">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 items-center justify-center
                                       rounded-xl bg-sky-50 text-sky-600"
                            >
                                <i class="bi bi-calendar3"></i>
                            </div>

                            <div>

                                <h2 class="text-lg font-extrabold text-slate-900">
                                    Offer Validity
                                </h2>

                                <p class="text-sm text-slate-500">
                                    Set the start and end date of this offer.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        {{-- Start Date --}}
                        <div>

                            <label
                                for="start_date"
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                Start Date
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="start_date"
                                type="date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                required
                                class="input-premium w-full rounded-xl border border-slate-200
                                       bg-white px-4 py-3 text-sm text-slate-800"
                            >

                            @error('start_date')
                                <p class="mt-1 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- End Date --}}
                        <div>

                            <label
                                for="end_date"
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                End Date
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                id="end_date"
                                type="date"
                                name="end_date"
                                value="{{ old('end_date') }}"
                                required
                                class="input-premium w-full rounded-xl border border-slate-200
                                       bg-white px-4 py-3 text-sm text-slate-800"
                            >

                            @error('end_date')
                                <p class="mt-1 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =========================================
                     IMAGE + STATUS
                ========================================== --}}
                <div class="p-6 sm:p-8">

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- IMAGE --}}
                        <div>

                            <label
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                Offer Image
                            </label>

                            <label
                                for="image"
                                class="upload-box flex min-h-[170px] cursor-pointer
                                       flex-col items-center justify-center
                                       rounded-2xl border-2 border-dashed
                                       border-slate-200 bg-slate-50 p-5 text-center"
                            >

                                <div
                                    class="mb-3 flex h-12 w-12 items-center justify-center
                                           rounded-xl bg-indigo-100 text-indigo-600"
                                >
                                    <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                                </div>

                                <span class="text-sm font-bold text-slate-700">
                                    Upload Offer Image
                                </span>

                                <span class="mt-1 text-xs text-slate-400">
                                    JPG, JPEG, PNG or WEBP
                                </span>

                                <input
                                    id="image"
                                    type="file"
                                    name="image"
                                    accept="image/jpeg,image/png,image/jpg,image/webp"
                                    class="hidden"
                                >

                            </label>

                            <p
                                id="image-name"
                                class="mt-2 hidden text-xs font-semibold text-indigo-600"
                            ></p>

                            @error('image')
                                <p class="mt-1 text-xs font-medium text-red-500">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- STATUS --}}
                        <div>

                            <label
                                for="status"
                                class="mb-2 block text-sm font-bold text-slate-700"
                            >
                                Offer Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="input-premium w-full rounded-xl border border-slate-200
                                       bg-white px-4 py-3 text-sm text-slate-800"
                            >

                                <option
                                    value="1"
                                    {{ old('status', '1') == '1' ? 'selected' : '' }}
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    {{ old('status') === '0' ? 'selected' : '' }}
                                >
                                    Inactive
                                </option>

                            </select>

                            <div
                                class="mt-4 rounded-2xl bg-slate-50 p-4"
                            >

                                <div class="flex items-start gap-3">

                                    <i class="bi bi-info-circle mt-0.5 text-indigo-500"></i>

                                    <p class="text-xs leading-5 text-slate-500">
                                        Active offers can be displayed and used by
                                        customers according to the configured dates.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================
                     FOOTER
                ========================================== --}}
                <div
                    class="flex flex-col-reverse gap-3 border-t border-slate-100
                           bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8"
                >

                    <a
                        href="{{ route('admin.offers.index') }}"
                        class="inline-flex items-center justify-center gap-2
                               rounded-xl border border-slate-200 bg-white
                               px-5 py-3 text-sm font-bold text-slate-600
                               transition hover:bg-slate-100"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2
                               rounded-xl bg-gradient-to-r from-indigo-600
                               to-violet-600 px-6 py-3 text-sm font-bold
                               text-white shadow-lg shadow-indigo-200
                               transition hover:-translate-y-0.5 hover:shadow-xl"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Save Offer
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<script>
    const imageInput = document.getElementById('image');
    const imageName = document.getElementById('image-name');

    if (imageInput) {

        imageInput.addEventListener('change', function () {

            if (this.files.length > 0) {

                imageName.textContent =
                    'Selected: ' + this.files[0].name;

                imageName.classList.remove('hidden');

            } else {

                imageName.textContent = '';
                imageName.classList.add('hidden');

            }

        });

    }
</script>

@endsection