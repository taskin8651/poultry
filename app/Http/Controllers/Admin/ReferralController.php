<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ReferralController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('referral_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $referrals = Referral::with(['referrer', 'referred', 'order'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total'    => Referral::count(),
            'rewarded' => Referral::where('status', 'rewarded')->count(),
            'pending'  => Referral::where('status', 'pending')->count(),
            'payout'   => Referral::where('status', 'rewarded')->sum('reward_amount'),
        ];

        return view('admin.referrals.index', compact('referrals', 'stats'));
    }
}
