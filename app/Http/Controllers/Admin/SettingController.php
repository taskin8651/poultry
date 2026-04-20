<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Setting;
use Illuminate\Http\Request;


class SettingController extends Controller
{

public function index()
{
    $setting = Setting::first();
    return view('admin.settings.index', compact('setting'));
}

public function update(Request $request)
{
    $setting = Setting::first();

    if (!$setting) {
        $setting = Setting::create($request->all());
    } else {
        $setting->update($request->all());
    }

    // logo
    if ($request->hasFile('logo')) {
        $setting->clearMediaCollection('logo');

        $setting->addMediaFromRequest('logo')
                ->toMediaCollection('logo');
    }

    // favicon
    if ($request->hasFile('favicon')) {
        $setting->clearMediaCollection('favicon');

        $setting->addMediaFromRequest('favicon')
                ->toMediaCollection('favicon');
    }

    return back()->with('success','Settings Updated');
}
}
