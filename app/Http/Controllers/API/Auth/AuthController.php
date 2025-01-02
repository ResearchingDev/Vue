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
        // Get user by email with the 'role' relationship
        $user = User::with('role')->where('email', $request->email)->first();

        // Check if user exists and either primary or secondary password matches
        if ($user && $this->checkPassword($request, $user)) {
            // Proceed to authenticate
            Auth::login($user);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'client_id' => $user->client_id,
                        'role_id' => $user->role_id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'phone_number' => $user->phone_number,
                        'alter_phone_number' => $user->alter_phone_number,
                        'status' => $user->status,
                        'user_type' => $user->user_type,
                        'can_login' => $user->can_login,
                        'role_name' => $user->role->role_name ?? null,
                        'role_code' => $user->role->role_unique_code ?? null,
                    ],
                    'token' => $user->createToken('api_token')->plainTextToken,
                ],
            ], 200);
        }

        // If user doesn't exist or passwords don't match
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
            'data' => [],
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
