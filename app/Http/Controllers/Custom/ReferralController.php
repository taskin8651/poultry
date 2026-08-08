<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $referrals = $user->referralsMade()
            ->with('referred')
            ->latest()
            ->get();

        $walletTransactions = $user->walletTransactions()
            ->latest()
            ->take(20)
            ->get();

        return view('custom.referrals.index', compact('user', 'referrals', 'walletTransactions'));
    }
}
