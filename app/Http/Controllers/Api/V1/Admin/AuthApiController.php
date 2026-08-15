<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Delete previous tokens
        $user->tokens()->delete();

        // Create new Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',

            'token' => $token,
            'token_type' => 'Bearer',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'address' => $user->address,
                'referral_code' => $user->referral_code,
                'referred_by' => $user->referred_by,
                'wallet_balance' => $user->wallet_balance,
            ],
        ], 200);
    }


    /**
     * User Register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'address' => 'required|string|max:500',

            // Existing user's referral code
            'refer_code' => 'required|string|size:10',

            'password' => 'required|string|min:6',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Find Referrer
        |--------------------------------------------------------------------------
        */

        $referrer = User::where(
            'referral_code',
            strtoupper($request->refer_code)
        )->first();

        if (!$referrer) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid referral code.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Self Referral
        |--------------------------------------------------------------------------
        */

        if (
            $referrer->email === $request->email ||
            $referrer->phone === $request->phone
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid referral.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | Create User + Referral
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            |
            | User model ke creating event se referral_code automatically
            | 10 character uppercase alphanumeric generate hoga.
            |
            */

            $user = User::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'password' => $request->password,

                // Referrer User ID
                'referred_by' => $referrer->id,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Referral Record
            |--------------------------------------------------------------------------
            */

            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'order_id' => null,
                'status' => 'pending',
                'reward_amount' => 0,
                'rewarded_at' => null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create Login Token
            |--------------------------------------------------------------------------
            */

            $token = $user->createToken('api-token')->plainTextToken;


            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Success Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' => 'Registration successful.',

                'token' => $token,
                'token_type' => 'Bearer',

                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'address' => $user->address,

                    // New user's automatically generated referral code
                    'referral_code' => $user->referral_code,

                    // Referrer user ID
                    'referred_by' => $user->referred_by,

                    'wallet_balance' => $user->wallet_balance,
                ],
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Registration failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}