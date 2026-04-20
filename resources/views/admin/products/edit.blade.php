@extends('layouts.admin')
@section('content')

<div class="max-w-6xl mx-auto">

<h2 class="text-2xl font-bold mb-6">Edit Product</h2>

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.products.update', $product->id) }}">
@csrf
@method('PUT')

<div class="grid grid-cols-2 gap-6">

    {{-- LEFT --}}
    <div>

        <label>Name</label>
        <input type="text" name="name"
               value="{{ $product->name }}"
               class="w-full border p-2 mb-3 rounded">

        <label>Category</label>
        <select name="category_id" class="w-full border p-2 mb-3 rounded">
            @foreach($categories as $id => $name)
                <option value="{{ $id }}"
                    {{ $product->category_id == $id ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>

        <label>Type</label>
        <select name="type" class="w-full border p-2 mb-3 rounded">
            <option value="egg" {{ $product->type=='egg'?'selected':'' }}>Egg</option>
            <option value="hen" {{ $product->type=='hen'?'selected':'' }}>Hen</option>
        </select>

        <label>Sale Type</label>
        <select name="sale_type" class="w-full border p-2 mb-3 rounded">
            <option value="tray" {{ $product->sale_type=='tray'?'selected':'' }}>Tray</option>
            <option value="piece" {{ $product->sale_type=='piece'?'selected':'' }}>Piece</option>
            <option value="weight" {{ $product->sale_type=='weight'?'selected':'' }}>Weight (Kg)</option>
        </select>

        <label>Base Price</label>
        <input type="number" step="0.01" name="base_price"
               value="{{ $product->base_price }}"
               class="w-full border p-2 mb-3 rounded">

        <label>Stock</label>
        <input type="number" name="stock"
               value="{{ $product->stock }}"
               class="w-full border p-2 mb-3 rounded">

    </div>

    {{-- RIGHT --}}
    <div>

        {{-- TAGS --}}
        <label>Tags</label>
        <div class="grid grid-cols-2 gap-2 border p-2 mb-3 rounded">
            @foreach($tags as $id => $tag)
                <label>
                    <input type="checkbox"
                           name="tags[]"
                           value="{{ $id }}"
                           {{ $product->tags->pluck('id')->contains($id) ? 'checked' : '' }}>
                    {{ $tag }}
                </label>
            @endforeach
        </div>

        {{-- DESCRIPTION --}}
        <label>Description</label>
        <textarea name="description"
                  class="w-full border p-2 mb-3 rounded">{{ $product->description }}</textarea>

        {{-- THUMBNAIL --}}
        <label>Current Thumbnail</label>
        <div class="mb-3">
            @if($product->getFirstMediaUrl('product_thumbnail'))
                <img src="{{ $product->getFirstMediaUrl('product_thumbnail') }}"
                     class="w-24 h-24 object-cover rounded border">
            @endif
        </div>

        <input type="file" name="thumbnail" class="mb-3">

        {{-- GALLERY --}}
        <label>Current Gallery</label>
        <div class="flex flex-wrap gap-3 mb-3">
            @foreach($product->getMedia('product_gallery') as $media)
                <img src="{{ $media->getUrl() }}"
                     class="w-20 h-20 object-cover rounded border">
            @endforeach
        </div>

        <input type="file" name="gallery[]" multiple class="mb-3">

    </div>

</div>

{{-- 🔥 BULK PRICING --}}
<div class="mt-6">

    <h3 class="text-lg font-semibold mb-3">Bulk Pricing</h3>

    <div id="bulk-wrapper">

        @foreach($product->bulkPrices as $bulk)
            <div class="flex gap-3 mb-2">
                <input type="number" name="bulk_qty[]"
                       value="{{ $bulk->min_qty }}"
                       class="border p-2 w-1/2">

                <input type="number" step="0.01" name="bulk_price[]"
                       value="{{ $bulk->price }}"
                       class="border p-2 w-1/2">
            </div>
        @endforeach

    </div>

    <button type="button" onclick="addBulk()"
            class="bg-gray-600 text-white px-3 py-1 mt-2 rounded">
        + Add More
    </button>

</div>

{{-- BUTTON --}}
<div class="mt-6">
    <button class="bg-green-600 text-white px-6 py-2 rounded">
        Update Product
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