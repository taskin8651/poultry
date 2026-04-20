<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // ✅ IMPORTANT FIX
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $service = Service::create($request->all());

        // 🔥 Image Upload
        if ($request->hasFile('image')) {
            $service->addMediaFromRequest('image')
                    ->toMediaCollection('service_image');
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service Created');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $service->update($request->all());

        if ($request->hasFile('image')) {
            $service->clearMediaCollection('service_image');

            $service->addMediaFromRequest('image')
                    ->toMediaCollection('service_image');
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service Updated');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', 'Deleted');
    }
}