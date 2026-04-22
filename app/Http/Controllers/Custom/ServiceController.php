<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('status', 1)->latest()->get();

        return view('custom.service', compact('services'));
    }

    public function show($id)
{
    $service = Service::findOrFail($id);

    // 🔥 sidebar ke liye sab services
    $services = Service::where('status', 1)->get();

    return view('custom.service-detail', compact('service', 'services'));
}

}