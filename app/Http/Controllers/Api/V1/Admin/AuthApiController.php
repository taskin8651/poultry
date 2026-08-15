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

        // Referral code is optional
        'refer_code' => 'nullable|string|size:10',

        'password' => 'required|string|min:6',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Find Referrer
    |--------------------------------------------------------------------------
    */

    $referrer = null;

    if ($request->filled('refer_code')) {

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
    }


    /*
    |--------------------------------------------------------------------------
    | Create User + Referral
    |--------------------------------------------------------------------------
    */

    DB::beginTransaction();

    try {

        // Create User
        // referral_code automatically User model se generate hoga
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'password' => $request->password,

            // Agar referral nahi hai to NULL
            'referred_by' => $referrer ? $referrer->id : null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Referral Record Only If Referral Code Provided
        |--------------------------------------------------------------------------
        */

        if ($referrer) {

            Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'order_id' => null,
                'status' => 'pending',
                'reward_amount' => 0,
                'rewarded_at' => null,
            ]);
        }


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

                // Automatically generated 10 digit/character referral code
                'referral_code' => $user->referral_code,

                // Referrer ID, otherwise null
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