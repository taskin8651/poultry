@extends('layouts.admin')

@section('content')

<div class="p-6 max-w-3xl mx-auto">

<h1 class="text-2xl font-bold mb-6">Website Settings</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="POST" enctype="multipart/form-data"
      action="{{ route('admin.settings.update') }}">
@csrf

{{-- BASIC --}}
<h2 class="font-semibold mb-2">Basic</h2>

<input name="site_name" value="{{ $setting->site_name ?? '' }}"
       placeholder="Site Name" class="w-full border p-2 mb-3">

<input name="tagline" value="{{ $setting->tagline ?? '' }}"
       placeholder="Tagline" class="w-full border p-2 mb-3">

{{-- LOGO --}}
@if($setting && $setting->getFirstMediaUrl('logo'))
<img src="{{ $setting->getFirstMediaUrl('logo') }}" class="w-24 mb-2">
@endif

<input type="file" name="logo" class="mb-3">

{{-- CONTACT --}}
<h2 class="font-semibold mt-4 mb-2">Contact</h2>

<input name="email" value="{{ $setting->email ?? '' }}"
       placeholder="Email" class="w-full border p-2 mb-3">

<input name="phone" value="{{ $setting->phone ?? '' }}"
       placeholder="Phone" class="w-full border p-2 mb-3">

<input name="address" value="{{ $setting->address ?? '' }}"
       placeholder="Address" class="w-full border p-2 mb-3">

{{-- SOCIAL --}}
<h2 class="font-semibold mt-4 mb-2">Social Links</h2>

<input name="facebook" value="{{ $setting->facebook ?? '' }}"
       placeholder="Facebook" class="w-full border p-2 mb-3">

<input name="instagram" value="{{ $setting->instagram ?? '' }}"
       placeholder="Instagram" class="w-full border p-2 mb-3">

<input name="twitter" value="{{ $setting->twitter ?? '' }}"
       placeholder="Twitter" class="w-full border p-2 mb-3">

{{-- SEO --}}
<h2 class="font-semibold mt-4 mb-2">SEO</h2>

<input name="meta_title" value="{{ $setting->meta_title ?? '' }}"
       placeholder="Meta Title" class="w-full border p-2 mb-3">

<textarea name="meta_description"
          class="w-full border p-2 mb-3"
          placeholder="Meta Description">{{ $setting->meta_description ?? '' }}</textarea>

{{-- FOOTER --}}
<h2 class="font-semibold mt-4 mb-2">Footer</h2>

<textarea name="footer_text"
          class="w-full border p-2 mb-3"
          placeholder="Footer Text">{{ $setting->footer_text ?? '' }}</textarea>

<button class="bg-blue-600 text-white px-6 py-2 rounded">
    Save Settings
</button>

</form>

</div>

@endsection