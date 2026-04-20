@extends('layouts.admin')
@section('content')

<h2 class="text-xl font-bold mb-4">Products</h2>

<a href="{{ route('admin.products.create') }}"
   class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
    + Add Product
</a>

<table class="w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Sale</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($products as $product)
        <tr class="border-t">
            <td>{{ $product->name }}</td>
            <td>{{ $product->type }}</td>
            <td>{{ $product->sale_type }}</td>
            <td>₹{{ $product->base_price }}</td>
            <td>{{ $product->stock }}</td>

            <td class="space-x-2">
                <a href="{{ route('admin.products.edit', $product->id) }}"
                   class="text-blue-600">Edit</a>

                <form action="{{ route('admin.products.destroy', $product->id) }}"
                      method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection