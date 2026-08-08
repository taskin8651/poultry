@extends('layouts.admin')

@section('content')

<style>
    .upload-box {
        transition: all .25s ease;
    }

    .upload-box:hover {
        border-color: #6366f1;
        background: #f8faff;
    }
</style>

<div class="mx-auto w-full max-w-5xl">

    {{-- =========================================
         HEADER
    ========================================== --}}
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">
                    Add Product
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Create a new wholesale product
                </p>
            </div>

        </div>

        <a href="{{ route('admin.products.index') }}" class="btn-premium btn-premium-outline">
            <i class="bi bi-arrow-left"></i>
            Back to Products
        </a>

    </div>


    {{-- =========================================
         FORM CARD
    ========================================== --}}
    <div class="card-premium overflow-hidden">

        <form method="POST" enctype="multipart/form-data"
              action="{{ route('admin.products.store') }}">
            @csrf

            {{-- =========================================
                 BASIC INFORMATION
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="bi bi-info-circle-fill"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Basic Information
                        </h2>
                        <p class="text-sm text-slate-500">
                            Name, category and description of the product.
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- NAME --}}
                    <div>
                        <label for="name" class="label-premium">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               placeholder="e.g. Farm Fresh Eggs"
                               class="input-premium">
                        @error('name')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CATEGORY --}}
                    <div>
                        <label for="category_id" class="label-premium">Category</label>
                        <select id="category_id" name="category_id" class="input-premium">
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- DESCRIPTION --}}
                <div class="mt-5">
                    <label for="description" class="label-premium">Description</label>
                    <textarea id="description" name="description" rows="4"
                              placeholder="Write a short description about this product..."
                              class="input-premium resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>


            {{-- =========================================
                 CLASSIFICATION
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <i class="bi bi-tags-fill"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Classification
                        </h2>
                        <p class="text-sm text-slate-500">
                            Choose the product type and how it is sold.
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- TYPE --}}
                    <div>
                        <label for="type" class="label-premium">Product Type</label>
                        <select id="type" name="type" class="input-premium">
                            <option value="egg">Egg</option>
                            <option value="hen">Hen</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- SALE TYPE --}}
                    <div>
                        <label for="sale_type" class="label-premium">Sale Type</label>
                        <select id="sale_type" name="sale_type" class="input-premium">
                            <option value="tray">Tray (Egg)</option>
                            <option value="piece">Piece (Hen)</option>
                            <option value="weight">Weight (Kg)</option>
                        </select>
                        @error('sale_type')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>


            {{-- =========================================
                 PRICING & STOCK
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <i class="bi bi-currency-rupee"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Pricing &amp; Stock
                        </h2>
                        <p class="text-sm text-slate-500">
                            Set the base market rate and available stock.
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- BASE PRICE --}}
                    <div>
                        <label for="base_price" class="label-premium">Base Price (Market Rate)</label>
                        <input type="number" step="0.01" id="base_price" name="base_price"
                               value="{{ old('base_price') }}" placeholder="e.g. 120.00"
                               class="input-premium">
                        @error('base_price')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- STOCK --}}
                    <div>
                        <label for="stock" class="label-premium">Stock</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock') }}"
                               placeholder="e.g. 100"
                               class="input-premium">
                        @error('stock')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>


            {{-- =========================================
                 BULK PRICING
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                        <i class="bi bi-layers-fill"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Bulk Pricing
                        </h2>
                        <p class="text-sm text-slate-500">
                            Offer special rates for minimum quantity purchases.
                        </p>
                    </div>

                </div>

                <div id="bulk-wrapper" class="space-y-3">

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input type="number" name="bulk_qty[]"
                               placeholder="Min Qty"
                               class="input-premium sm:w-1/2">

                        <input type="number" step="0.01" name="bulk_price[]"
                               placeholder="Price"
                               class="input-premium sm:w-1/2">
                    </div>

                </div>

                <button type="button" onclick="addBulk()"
                        class="btn-premium btn-premium-outline mt-4">
                    <i class="bi bi-plus-lg"></i>
                    Add More
                </button>

            </div>


            {{-- =========================================
                 TAGS
            ========================================== --}}
            <div class="border-b border-slate-100 p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <i class="bi bi-hash"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Tags
                        </h2>
                        <p class="text-sm text-slate-500">
                            Select tags to help customers find this product.
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:grid-cols-3">
                    @foreach($tags as $id => $tag)
                        <label class="flex items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="tags[]" value="{{ $id }}"
                                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            {{ $tag }}
                        </label>
                    @endforeach
                </div>

            </div>


            {{-- =========================================
                 MEDIA
            ========================================== --}}
            <div class="p-6 sm:p-8">

                <div class="mb-6 flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                        <i class="bi bi-images"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">
                            Media
                        </h2>
                        <p class="text-sm text-slate-500">
                            Upload a thumbnail and gallery images for this product.
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- THUMBNAIL --}}
                    <div>
                        <label class="label-premium">Thumbnail</label>

                        <label for="thumbnail"
                               class="upload-box flex min-h-[170px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-5 text-center">

                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                                <i class="bi bi-cloud-arrow-up-fill text-xl"></i>
                            </div>

                            <span class="text-sm font-bold text-slate-700">
                                Upload Thumbnail
                            </span>

                            <span class="mt-1 text-xs text-slate-400">
                                JPG, JPEG, PNG or WEBP
                            </span>

                            <input id="thumbnail" type="file" name="thumbnail"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="hidden">

                        </label>

                        <p id="thumbnail-name" class="mt-2 hidden text-xs font-semibold text-indigo-600"></p>

                        @error('thumbnail')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- GALLERY --}}
                    <div>
                        <label class="label-premium">Gallery Images</label>

                        <label for="gallery"
                               class="upload-box flex min-h-[170px] cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-5 text-center">

                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-violet-100 text-violet-600">
                                <i class="bi bi-collection-fill text-xl"></i>
                            </div>

                            <span class="text-sm font-bold text-slate-700">
                                Upload Gallery Images
                            </span>

                            <span class="mt-1 text-xs text-slate-400">
                                You can select multiple images
                            </span>

                            <input id="gallery" type="file" name="gallery[]" multiple
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="hidden">

                        </label>

                        <p id="gallery-name" class="mt-2 hidden text-xs font-semibold text-indigo-600"></p>

                        @error('gallery')
                            <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>


            {{-- =========================================
                 FOOTER
            ========================================== --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 p-6 sm:flex-row sm:justify-end sm:p-8">

                <a href="{{ route('admin.products.index') }}" class="btn-premium btn-premium-outline">
                    Cancel
                </a>

                <button type="submit" class="btn-premium btn-premium-primary">
                    <i class="bi bi-check2-circle"></i>
                    Save Product
                </button>

            </div>

        </form>

    </div>

</div>


{{-- JS --}}
<script>
function addBulk() {
    document.getElementById('bulk-wrapper').innerHTML += `
        <div class="flex flex-col gap-3 sm:flex-row">
            <input type="number" name="bulk_qty[]" placeholder="Min Qty" class="input-premium sm:w-1/2">
            <input type="number" step="0.01" name="bulk_price[]" placeholder="Price" class="input-premium sm:w-1/2">
        </div>
    `;
}

const thumbnailInput = document.getElementById('thumbnail');
const thumbnailName = document.getElementById('thumbnail-name');

if (thumbnailInput) {
    thumbnailInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            thumbnailName.textContent = 'Selected: ' + this.files[0].name;
            thumbnailName.classList.remove('hidden');
        } else {
            thumbnailName.textContent = '';
            thumbnailName.classList.add('hidden');
        }
    });
}

const galleryInput = document.getElementById('gallery');
const galleryName = document.getElementById('gallery-name');

if (galleryInput) {
    galleryInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            galleryName.textContent = this.files.length + ' file(s) selected';
            galleryName.classList.remove('hidden');
        } else {
            galleryName.textContent = '';
            galleryName.classList.add('hidden');
        }
    });
}
</script>

@endsection
