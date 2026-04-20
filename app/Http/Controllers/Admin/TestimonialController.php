<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $testimonial = Testimonial::create($request->all());

        if ($request->hasFile('image')) {
            $testimonial->addMediaFromRequest('image')
                        ->toMediaCollection('testimonial_image');
        }

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Created');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $testimonial->update($request->all());

        if ($request->hasFile('image')) {
            $testimonial->clearMediaCollection('testimonial_image');

            $testimonial->addMediaFromRequest('image')
                        ->toMediaCollection('testimonial_image');
        }

        return redirect()->route('admin.testimonials.index')
            ->with('success', 'Updated');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return back()->with('success', 'Deleted');
    }
}