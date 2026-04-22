<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\About;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Service;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $heroes = Hero::where('status', 1)->latest()->get();
        $about = About::where('status', 1)->latest()->first();

        $offers = Offer::where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->get();
        
        $products = Product::where('status', 1)->latest()->take(6)->get();

        $testimonials = Testimonial::where('status', 1)->latest()->get();

        $services = Service::where('status', 1)->latest()->get();

         $setting = Setting::first();



        return view('custom.home', compact('heroes', 'about', 'offers', 'products', 'testimonials', 'services','setting'));
    }
}