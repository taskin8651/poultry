@extends('layouts.admin')

@section('page-title', 'Settings')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-8 flex items-center gap-4">
        <div class="page-icon">
            <i class="bi bi-gear-fill"></i>
        </div>
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900">
                Website Settings
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Manage your site identity, contact info and SEO details.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-premium-success mb-6">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card-premium overflow-hidden">
        <form method="POST" enctype="multipart/form-data"
              action="{{ route('admin.settings.update') }}">
            @csrf

            {{-- BASIC --}}
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Basic Information</h2>
                        <p class="text-sm text-slate-500">Your site's name and tagline.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="label-premium">Site Name</label>
                        <input name="site_name" value="{{ $setting->site_name ?? '' }}"
                               placeholder="Site Name" class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium">Tagline</label>
                        <input name="tagline" value="{{ $setting->tagline ?? '' }}"
                               placeholder="Tagline" class="input-premium">
                    </div>
                </div>

                <div class="mt-5">
                    <label class="label-premium">Logo</label>
                    <div class="flex items-center gap-4">
                        @if($setting && $setting->getFirstMediaUrl('logo'))
                            <img src="{{ $setting->getFirstMediaUrl('logo') }}"
                                 class="h-16 w-16 rounded-xl object-cover border border-slate-200">
                        @endif
                        <input type="file" name="logo"
                               class="input-premium file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-600 file:px-3 file:py-1.5 file:text-sm file:font-bold">
                    </div>
                </div>
            </div>

            {{-- CONTACT --}}
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Contact Details</h2>
                        <p class="text-sm text-slate-500">How customers can reach you.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="label-premium">Email</label>
                        <input name="email" value="{{ $setting->email ?? '' }}"
                               placeholder="Email" class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium">Phone</label>
                        <input name="phone" value="{{ $setting->phone ?? '' }}"
                               placeholder="Phone" class="input-premium">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label-premium">Address</label>
                        <input name="address" value="{{ $setting->address ?? '' }}"
                               placeholder="Address" class="input-premium">
                    </div>
                </div>
            </div>

            {{-- SOCIAL --}}
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center">
                        <i class="bi bi-share"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Social Links</h2>
                        <p class="text-sm text-slate-500">Connect your social media profiles.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="label-premium"><i class="bi bi-facebook mr-1 text-blue-600"></i> Facebook</label>
                        <input name="facebook" value="{{ $setting->facebook ?? '' }}"
                               placeholder="Facebook URL" class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium"><i class="bi bi-instagram mr-1 text-pink-600"></i> Instagram</label>
                        <input name="instagram" value="{{ $setting->instagram ?? '' }}"
                               placeholder="Instagram URL" class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium"><i class="bi bi-twitter-x mr-1 text-slate-800"></i> Twitter</label>
                        <input name="twitter" value="{{ $setting->twitter ?? '' }}"
                               placeholder="Twitter URL" class="input-premium">
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="bi bi-search"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">SEO</h2>
                        <p class="text-sm text-slate-500">Improve how your site appears in search results.</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="label-premium">Meta Title</label>
                        <input name="meta_title" value="{{ $setting->meta_title ?? '' }}"
                               placeholder="Meta Title" class="input-premium">
                    </div>
                    <div>
                        <label class="label-premium">Meta Description</label>
                        <textarea name="meta_description" rows="3"
                                  class="input-premium resize-none"
                                  placeholder="Meta Description">{{ $setting->meta_description ?? '' }}</textarea>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="bi bi-layout-text-window"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Footer</h2>
                        <p class="text-sm text-slate-500">Text shown in your site footer.</p>
                    </div>
                </div>

                <textarea name="footer_text" rows="3"
                          class="input-premium resize-none"
                          placeholder="Footer Text">{{ $setting->footer_text ?? '' }}</textarea>
            </div>

            {{-- REFERRAL PROGRAM --}}
            <div class="p-6 sm:p-8 border-b border-slate-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="h-10 w-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <i class="bi bi-gift-fill"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">Referral Program</h2>
                        <p class="text-sm text-slate-500">Reward customers when someone they invite places their first order.</p>
                    </div>
                </div>

                <div class="max-w-xs">
                    <label class="label-premium">Referral Reward Amount (₹)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₹</span>
                        <input type="number" step="0.01" min="0" name="referral_reward_amount"
                               value="{{ old('referral_reward_amount', $setting->referral_reward_amount ?? 0) }}"
                               placeholder="0.00" class="input-premium !pl-8">
                    </div>
                    <p class="mt-2 text-xs text-slate-400">
                        Credited to the referrer's wallet the moment the person they invited places their first order. Set to 0 to disable rewards.
                    </p>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex justify-end gap-3 bg-slate-50/70 p-6 sm:p-8">
                <button type="submit" class="btn-premium btn-premium-primary">
                    <i class="bi bi-check2-circle"></i>
                    Save Settings
                </button>
            </div>

        </form>
    </div>

</div>

@endsection