<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * User Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Purane tokens delete
        $user->tokens()->delete();

        // New Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        // Referral data
        $referrals = $user->referralsMade()
            ->with([
                'referred:id,name,email,phone,referral_code',
                'order:id'
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'Bearer',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'referral_code' => $user->referral_code,
                'referred_by' => $user->referred_by,
                'wallet_balance' => $user->wallet_balance,

                // Referral summary
                'referral_count' => $referrals->count(),
                'total_reward' => $referrals->sum('reward_amount'),

                // Referral list
                'referrals' => $referrals->map(function ($referral) {
                    return [
                        'id' => $referral->id,
                        'referred_user_id' => $referral->referred_id,
                        'referred_user' => $referral->referred ? [
                            'id' => $referral->referred->id,
                            'name' => $referral->referred->name,
                            'email' => $referral->referred->email,
                            'phone' => $referral->referred->phone,
                            'referral_code' => $referral->referred->referral_code,
                        ] : null,

                        'order_id' => $referral->order_id,
                        'status' => $referral->status,
                        'reward_amount' => $referral->reward_amount,
                        'rewarded_at' => $referral->rewarded_at,
                    ];
                })->values(),
            ],
        ], 200);
    }


    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }


    /**
     * Logged-in User
     */
    public function user(Request $request)
    {
        $user = $request->user();

        $referrals = $user->referralsMade()
            ->with([
                'referred:id,name,email,phone,referral_code',
                'order:id'
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'referral_code' => $user->referral_code,
                'referred_by' => $user->referred_by,
                'wallet_balance' => $user->wallet_balance,

                'referral_count' => $referrals->count(),
                'total_reward' => $referrals->sum('reward_amount'),

                'referrals' => $referrals->map(function ($referral) {
                    return [
                        'id' => $referral->id,
                        'referred_user_id' => $referral->referred_id,

                        'referred_user' => $referral->referred ? [
                            'id' => $referral->referred->id,
                            'name' => $referral->referred->name,
                            'email' => $referral->referred->email,
                            'phone' => $referral->referred->phone,
                            'referral_code' => $referral->referred->referral_code,
                        ] : null,

                        'order_id' => $referral->order_id,
                        'status' => $referral->status,
                        'reward_amount' => $referral->reward_amount,
                        'rewarded_at' => $referral->rewarded_at,
                    ];
                })->values(),
            ],
        ]);
    }
}