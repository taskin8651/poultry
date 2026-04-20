<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = About::latest()->get();
        return view('admin.abouts.index', compact('abouts'));
    }

    public function create()
    {
        return view('admin.abouts.create');
    }

    public function store(Request $request)
    {
        $about = About::create($request->all());

        // 🔥 image upload
        if ($request->hasFile('image')) {
            $about->addMediaFromRequest('image')
                  ->toMediaCollection('about_image');
        }

        return redirect()->route('admin.abouts.index')
            ->with('success', 'About created');
    }

    public function edit(About $about)
    {
        return view('admin.abouts.edit', compact('about'));
    }

    public function update(Request $request, About $about)
    {
        $about->update($request->all());

        if ($request->hasFile('image')) {
            $about->clearMediaCollection('about_image');

            $about->addMediaFromRequest('image')
                  ->toMediaCollection('about_image');
        }

        return redirect()->route('admin.abouts.index')
            ->with('success', 'Updated');
    }

    public function destroy(About $about)
    {
        $about->delete();

        return back()->with('success', 'Deleted');
    }
}