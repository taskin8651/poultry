<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // -------------------------------------------------------------------------
    // GET /account — dashboard
    // -------------------------------------------------------------------------
    public function dashboard()
    {
        $user = Auth::user();

        $stats = [
            'total_orders'   => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)->where('status', 'pending')->count(),
            'wallet_balance' => $user->wallet_balance,
            'referrals'      => $user->referralsMade()->count(),
        ];

        $recentOrders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('custom.account.dashboard', compact('user', 'stats', 'recentOrders'));
    }

    // -------------------------------------------------------------------------
    // GET /account/profile
    // -------------------------------------------------------------------------
    public function editProfile()
    {
        return view('custom.account.profile', ['user' => Auth::user()]);
    }

    // -------------------------------------------------------------------------
    // PUT /account/profile
    // -------------------------------------------------------------------------
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    // -------------------------------------------------------------------------
    // GET /account/password
    // -------------------------------------------------------------------------
    public function editPassword()
    {
        return view('custom.account.password');
    }

    // -------------------------------------------------------------------------
    // PUT /account/password
    // -------------------------------------------------------------------------
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}
