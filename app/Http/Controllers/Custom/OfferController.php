<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Offer;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->get();

        return view('custom.offers', compact('offers'));
    }
}