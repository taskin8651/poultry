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
        $data = $this->validated($request);

        $offer = Offer::create($data);

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
        $data = $this->validated($request);

        $offer->update($data);

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

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'applies_to'      => ['required', 'in:all,egg,hen'],
            'condition_type'  => ['required', 'in:price,kg,piece,qty'],
            'condition_value' => ['required', 'numeric', 'min:0'],
            'reward_kind'     => ['required', 'in:fixed,percent'],
            'reward_value'    => ['required', 'numeric', 'min:0', $request->input('reward_kind') === 'percent' ? 'max:100' : 'max:999999'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after_or_equal:start_date'],
            'status'          => ['nullable', 'boolean'],
            'image'           => ['nullable', 'image', 'max:4096'],
        ]);

        unset($validated['image']);
        $validated['status'] = $request->boolean('status');

        return $validated;
    }
}
