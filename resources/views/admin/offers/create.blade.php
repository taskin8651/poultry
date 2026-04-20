@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-xl mx-auto">

<h1 class="text-xl font-bold mb-4">Add Offer</h1>

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.offers.store') }}">
@csrf

<input name="title" placeholder="Title" class="w-full border p-2 mb-3">

<textarea name="description" placeholder="Description"
          class="w-full border p-2 mb-3"></textarea>

<input name="min_amount" placeholder="Target Amount"
       class="w-full border p-2 mb-3">

<select name="reward_type" class="w-full border p-2 mb-3">
    <option value="discount">Discount</option>
    <option value="gift">Gift</option>
</select>

<input name="reward_value" placeholder="Reward Value"
       class="w-full border p-2 mb-3">

<input type="date" name="start_date" class="w-full border p-2 mb-3">
<input type="date" name="end_date" class="w-full border p-2 mb-3">

<input type="file" name="image" class="mb-3">

<select name="status" class="w-full border p-2 mb-3">
    <option value="1">Active</option>
    <option value="0">Inactive</option>
</select>

<button class="bg-blue-600 text-white px-4 py-2 rounded">
    Save
</button>

</form>

</div>

@endsection