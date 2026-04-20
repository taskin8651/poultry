@extends('layouts.admin')

@section('content')

<div class="p-6">

    <div class="flex justify-between mb-4">
        <h1 class="text-2xl font-bold">Offers</h1>

        <a href="{{ route('admin.offers.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded">
            + Add Offer
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full bg-white shadow rounded">

        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Image</th>
                <th>Title</th>
                <th>Target</th>
                <th>Reward</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        @foreach($offers as $offer)
            <tr class="border-t">

                <td class="p-3">
                    <img src="{{ $offer->getFirstMediaUrl('offer_image') }}"
                         class="w-16 h-12 object-cover">
                </td>

                <td>{{ $offer->title }}</td>

                <td>₹{{ $offer->min_amount }}</td>

                <td>
                    {{ $offer->reward_type }} ({{ $offer->reward_value }})
                </td>

                <td>
                    {{ $offer->status ? 'Active' : 'Inactive' }}
                </td>

                <td class="flex gap-2 p-3">
                    <a href="{{ route('admin.offers.edit',$offer->id) }}"
                       class="bg-yellow-500 text-white px-2 py-1 rounded">
                        Edit
                    </a>

                    <form method="POST"
                          action="{{ route('admin.offers.destroy',$offer->id) }}">
                        @csrf
                        @method('DELETE')

                        <button class="bg-red-600 text-white px-2 py-1 rounded">
                            Delete
                        </button>
                    </form>
                </td>

            </tr>
        @endforeach
        </tbody>

    </table>

</div>

@endsection