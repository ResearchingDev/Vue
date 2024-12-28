<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle user login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Get user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and either primary or secondary password matches
        if ($user && $this->checkPassword($request, $user)) {
            // Proceed to authenticate
            Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $user->createToken('api_token')->plainTextToken,
                ]
            ], 200);
        }
        // If user doesn't exist or neither password matches
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
            'data' => []
        ], 401);
    }
    /**
     * Check if either primary or secondary password matches.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return bool
     */
    protected function checkPassword(Request $request, $user)
    {
        return Hash::check($request->password, $user->password) || Hash::check($request->secondary_password, $user->secondary_password);
    }

    /**
     * Handle user logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Retrieve the authenticated user and revoke all tokens
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
            'data' => null,
        ], 200);
    }
}
