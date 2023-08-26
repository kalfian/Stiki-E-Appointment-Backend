<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (!Auth::attemptWhen($credentials, function ($user) {
            return $user->hasRole(role()::ROLE_STUDENT) || $user->hasRole(role()::ROLE_LECTURE);
        })) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $token = Auth::user()->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'data' => [
                'token' => $token,
                'user' => Auth::user(),
            ]
        ], 200);
    }

    public function logout(Request $request) {
        try {
            Auth::user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
            ], 500);
        }
    }
}
