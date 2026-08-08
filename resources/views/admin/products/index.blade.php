@extends('layouts.admin')

@section('content')

<div class="mx-auto w-full">

    {{-- =====================================================
         HEADER
    ====================================================== --}}
    <div class="mb-8 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="page-icon">
                <i class="bi bi-box-seam-fill"></i>
            </div>

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                        Products
                    </h1>

                    <span class="badge-premium badge-info">
                        {{ $products->total() }} Products
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Manage your wholesale product catalog
                </p>

            </div>

        </div>


        <a href="{{ route('admin.products.create') }}" class="btn-premium btn-premium-primary">
            <i class="bi bi-plus-lg"></i>
            Add Product
        </a>

    </div>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}
    @if(session('success'))

        <div class="alert-premium-success mb-6">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- =====================================================
         PRODUCTS TABLE
    ====================================================== --}}
    @if($products->count())

        <div class="card-premium overflow-hidden">

            <div class="table-premium-wrap">

                <table class="table-premium">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Sale Type</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="font-bold text-slate-800">
                                    {{ $product->name }}
                                </div>
                                <div class="mt-0.5 text-xs text-slate-400">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </div>
                            </td>

                            <td>
                                <span class="badge-premium badge-info">
                                    {{ ucfirst($product->type) }}
                                </span>
                            </td>

                            <td>
                                <span class="badge-premium badge-neutral">
                                    {{ ucfirst($product->sale_type) }}
                                </span>
                            </td>

                            <td class="font-bold text-slate-800">
                                &#8377;{{ number_format((float) $product->base_price, 2) }}
                            </td>

                            <td>
                                @if($product->stock > 5)
                                    <span class="badge-premium badge-success">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $product->stock }} In Stock
                                    </span>
                                @else
                                    <span class="badge-premium badge-danger">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                        Low Stock ({{ $product->stock }})
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                       class="btn-premium btn-premium-amber !px-3 !py-2 !text-xs">
                                        <i class="bi bi-pencil-square"></i>
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn-premium btn-premium-danger !px-3 !py-2 !text-xs">
                                            <i class="bi bi-trash3"></i>
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>

    @else

        {{-- =====================================================
             EMPTY STATE
        ====================================================== --}}
        <div class="card-premium px-6 py-20 text-center">

            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-600">
                <i class="bi bi-box-seam text-4xl"></i>
            </div>

            <h2 class="mt-6 text-xl font-extrabold text-slate-900">
                No Products Yet
            </h2>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                Start building your catalog by adding your first wholesale product.
            </p>

            <a href="{{ route('admin.products.create') }}" class="btn-premium btn-premium-primary mt-6">
                <i class="bi bi-plus-lg"></i>
                Add First Product
            </a>

        </div>

    @endif

</div>

@endsection
