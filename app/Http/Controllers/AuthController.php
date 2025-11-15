<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register()
    {
        $validated = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors(),
                'message' => 'Validation failed'
            ], 422);
        }
        $user = \App\Models\User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        // Auto-login after registration
        $credentials = request()->only('email', 'password');
        $token = Auth::attempt($credentials);

        return response()->json(
            [
                'message' => 'Registration successful',
                'data' => $user,
                'token' => $this->respondWithToken($token),
            ],
            201
        );
    }

    public function login(Request $request)
    {
        // Validate the request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $token = Auth::attempt($credentials);
        // Attempt to authenticate the user
        if (!$token) {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Authentication successful
        return response()->json([
            'message' => 'Login successful',
            'token' => $this->respondWithToken($token),
        ], 200);
    }

    public function user()
    {
        if (!Auth::user()) {
            return response()->json(
                [
                    'data' => null,
                    'message' => 'No authenticated user'
                ],
                401
            );
        }
        return response()->json([
            'data' => auth()->user(),
            'message' => 'Authenticated user retrieved successfully'
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Logout successful'], 200);
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1440
        ];
    }
}
