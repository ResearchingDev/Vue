<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Models\SubUserLogHistory;

class AuthController extends Controller
{
    /**
     * Handle user login
     */
    public function login(LoginRequest $request)
    {
        $user = User::with('role')->where('email', $request->email)->first();

        if ($user && $this->checkPassword($request, $user)) {
            $remember = $request->has('remember_me') && $request->remember_me;
            Auth::login($user, $remember);
            // Log user login
            $this->storeLogHistory($user, $request, 'login');
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
                        'profile_picture' => $user->profile_picture,
                        'can_login' => $user->can_login,
                        'role_name' => $user->role->role_name ?? null,
                        'role_code' => $user->role->role_unique_code ?? null,
                    ],
                    'token' => $user->createToken('api_token')->plainTextToken,
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
            'data' => [],
        ], 401);
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        // Log user logout
        $this->storeLogHistory($user, $request, 'logout');
        // Revoke all tokens
        $user->tokens->each(function ($token) {
            $token->delete();
        });
        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
            'data' => null,
        ], 200);
    }

    /**
     * Store or update log history for a user.
     */
    private function storeLogHistory(User $user, Request $request, $action)
    {
        $logId = $user->log_id;
        $logData = [
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_type' => $user->user_type,
            'browser_details' => $request->header('User-Agent'),
            'status' => $action === 'login' ? 'Active' : 'Inactive',
            'updated_by' => $user->id,
        ];

        if ($action === 'login') {
            $logData['login_at'] = now();
            $logData['login_source'] = 'Web';
        } else if ($action === 'logout') {
            $logData['logout_at'] = now();
            $logData['logout_source'] = 'Web';
        }

        if ($logId) {
            $log = SubUserLogHistory::find($logId);
            if ($log) {
                $log->update($logData);
            } else {
                $log = SubUserLogHistory::create($logData);
                $logId = $log->id;
            }
        } else {
            $log = SubUserLogHistory::create($logData);
            $logId = $log->id;
        }
        $user->log_id = $logId;
        return $logId;
    }

    /**
     * Check if either primary or secondary password matches.
     */
    protected function checkPassword(Request $request, $user)
    {
        return Hash::check($request->password, $user->password) || Hash::check($request->secondary_password, $user->secondary_password);
    }
}
