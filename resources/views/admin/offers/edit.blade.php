@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-xl mx-auto">

<h1 class="text-xl font-bold mb-4">Edit Offer</h1>

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.offers.update', $offer->id) }}">
@csrf
@method('PUT')

<input name="title" value="{{ $offer->title }}"
       class="w-full border p-2 mb-3">

<textarea name="description"
          class="w-full border p-2 mb-3">{{ $offer->description }}</textarea>

<input name="min_amount" value="{{ $offer->min_amount }}"
       class="w-full border p-2 mb-3">

<select name="reward_type" class="w-full border p-2 mb-3">
    <option value="discount" {{ $offer->reward_type=='discount'?'selected':'' }}>Discount</option>
    <option value="gift" {{ $offer->reward_type=='gift'?'selected':'' }}>Gift</option>
</select>

<input name="reward_value" value="{{ $offer->reward_value }}"
       class="w-full border p-2 mb-3">

<input type="date" name="start_date" value="{{ $offer->start_date }}"
       class="w-full border p-2 mb-3">

<input type="date" name="end_date" value="{{ $offer->end_date }}"
       class="w-full border p-2 mb-3">

{{-- IMAGE --}}
@if($offer->getFirstMediaUrl('offer_image'))
    <img src="{{ $offer->getFirstMediaUrl('offer_image') }}"
         class="w-24 mb-2">
@endif

<input type="file" name="image" class="mb-3">

<select name="status" class="w-full border p-2 mb-3">
    <option value="1" {{ $offer->status?'selected':'' }}>Active</option>
    <option value="0" {{ !$offer->status?'selected':'' }}>Inactive</option>
</select>

<button class="bg-green-600 text-white px-4 py-2 rounded">
    Update
</button>

</form>

</div>

@endsection