<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Exports\ResetPasswordUser;

use Maatwebsite\Excel\Facades\Excel;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function signIn(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attemptWhen($credentials, function ($user) {
            return $user->hasRole(role()::ROLE_ADMIN) || $user->hasRole(role()::ROLE_SUPERADMIN);
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

    public function resetPassword(Request $request) {

    }

    public function exportResetPassword(Request $request) {
        $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user_ids = $request->input('user_ids');

        $export = new ResetPasswordUser($user_ids);
        $timeNow = date("Ymdhis");

        return Excel::download($export, "reset-password-user-$timeNow.xlsx");

    }
}
