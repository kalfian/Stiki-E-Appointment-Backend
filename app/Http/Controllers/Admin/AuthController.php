<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function signIn(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attemptWhen($credentials, function ($user) {
            return $user->hasRole('admin') || $user->hasRole('super-admin');
        })) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withInput()->withError('Invalid credentials');
    }

    public function signOut(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }
}
