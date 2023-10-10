<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserResource;

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
                'message' => 'Email / Password Salah',
            ], 401);
        }

        $user = Auth::user();
        if ($user->active_status == referenceStatus()::STATUS_INACTIVE) {
            return response()->json([
                'message' => 'Akun anda belum aktif, silahkan hubungi admin',
            ], 401);
        }

        $token = Auth::user()->createToken('mobile_token')->plainTextToken;

        return response()->json([
            'message' => 'Login Berhasil',
            'data' => [
                'token' => $token,
                'user' => new UserResource(Auth::user()),
            ]
        ], 200);
    }

    public function logout(Request $request) {
        try {
            Auth::user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout Berhasil',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout Gagal',
            ], 500);
        }
    }

    public function attachFcmToken(Request $request) {
        // validate
        $rules = [
            'fcm_token' => 'required',
        ];

        $messages = [
            'fcm_token.required' => 'Token tidak boleh kosong',
        ];

        $this->validate($request, $rules, $messages);

        $user = Auth::user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'message' => 'Berhasil menyimpan token',
        ]);

    }
}
