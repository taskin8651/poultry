<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::latest()->get();
        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $offer = Offer::create($request->all());

        // 🔥 image upload
        if ($request->hasFile('image')) {
            $offer->addMediaFromRequest('image')
                  ->toMediaCollection('offer_image');
        }

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer Created');
    }

    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        $offer->update($request->all());

        if ($request->hasFile('image')) {
            $offer->clearMediaCollection('offer_image');

            $offer->addMediaFromRequest('image')
                  ->toMediaCollection('offer_image');
        }

        return redirect()->route('admin.offers.index')
            ->with('success', 'Updated');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return back()->with('success', 'Deleted');
    }
}