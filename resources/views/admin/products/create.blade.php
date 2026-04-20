@extends('layouts.admin')
@section('content')

<div class="max-w-5xl mx-auto">

<h2 class="text-2xl font-bold mb-6">Add Product (Wholesale)</h2>

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.products.store') }}">
@csrf

<div class="grid grid-cols-2 gap-6">

    {{-- LEFT --}}
    <div>

        {{-- NAME --}}
        <label>Name</label>
        <input type="text" name="name"
               class="w-full border p-2 mb-3 rounded">

        {{-- CATEGORY --}}
        <label>Category</label>
        <select name="category_id" class="w-full border p-2 mb-3 rounded">
            @foreach($categories as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>

        {{-- TYPE --}}
        <label>Product Type</label>
        <select name="type" class="w-full border p-2 mb-3 rounded">
            <option value="egg">Egg</option>
            <option value="hen">Hen</option>
        </select>

        {{-- SALE TYPE --}}
        <label>Sale Type</label>
        <select name="sale_type" class="w-full border p-2 mb-3 rounded">
            <option value="tray">Tray (Egg)</option>
            <option value="piece">Piece (Hen)</option>
            <option value="weight">Weight (Kg)</option>
        </select>

        {{-- BASE PRICE --}}
        <label>Base Price (Market Rate)</label>
        <input type="number" step="0.01" name="base_price"
               class="w-full border p-2 mb-3 rounded">

        {{-- STOCK --}}
        <label>Stock</label>
        <input type="number" name="stock"
               class="w-full border p-2 mb-3 rounded">

    </div>

    {{-- RIGHT --}}
    <div>

        {{-- TAGS --}}
        <label>Tags</label>
        <div class="grid grid-cols-2 gap-2 border p-2 mb-3 rounded">
            @foreach($tags as $id => $tag)
                <label>
                    <input type="checkbox" name="tags[]" value="{{ $id }}">
                    {{ $tag }}
                </label>
            @endforeach
        </div>

        {{-- DESCRIPTION --}}
        <label>Description</label>
        <textarea name="description"
                  class="w-full border p-2 mb-3 rounded"></textarea>

        {{-- THUMBNAIL --}}
        <label>Thumbnail</label>
        <input type="file" name="thumbnail"
               class="w-full mb-3">

        {{-- GALLERY --}}
        <label>Gallery Images</label>
        <input type="file" name="gallery[]"
               multiple class="w-full mb-3">

    </div>

</div>

{{-- 🔥 BULK PRICING --}}
<div class="mt-6">

    <h3 class="text-lg font-semibold mb-3">Bulk Pricing</h3>

    <div id="bulk-wrapper">

        <div class="flex gap-3 mb-2">
            <input type="number" name="bulk_qty[]"
                   placeholder="Min Qty"
                   class="border p-2 w-1/2">

            <input type="number" step="0.01" name="bulk_price[]"
                   placeholder="Price"
                   class="border p-2 w-1/2">
        </div>

    </div>

    <button type="button" onclick="addBulk()"
            class="bg-gray-600 text-white px-3 py-1 mt-2 rounded">
        + Add More
    </button>

</div>

{{-- BUTTON --}}
<div class="mt-6">
    <button class="bg-green-600 text-white px-6 py-2 rounded">
        Save Product
    </button>
</div>

</form>

</div>

{{-- JS --}}
<script>
function addBulk() {
    document.getElementById('bulk-wrapper').innerHTML += `
        <div class="flex gap-3 mb-2">
            <input type="number" name="bulk_qty[]" placeholder="Min Qty" class="border p-2 w-1/2">
            <input type="number" name="bulk_price[]" placeholder="Price" class="border p-2 w-1/2">
        </div>
    `;
}
</script>

@endsection