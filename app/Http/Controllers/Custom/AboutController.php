<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Testimonial;

class AboutController extends Controller
{
    public function index()
{
    $about = About::where('status', 1)->latest()->first();

    $testimonials = Testimonial::where('status', 1)->latest()->get();

    return view('custom.about', compact('about', 'testimonials'));
}
}