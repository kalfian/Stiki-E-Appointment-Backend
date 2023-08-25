<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Imports\UserImport;
use App\Models\FileLog;
use DB;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ResetPasswordUser;

use App\Jobs\ImportUserJob;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function signIn(Request $request) {
        $credentials = $request->only('email', 'password');
        $rememberMe = $request->input('remember_me');

        if (Auth::attemptWhen($credentials, function ($user) {
            return $user->hasRole(role()::ROLE_ADMIN) || $user->hasRole(role()::ROLE_SUPERADMIN);
        }, $rememberMe)) {
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
            'use_default_password' => ['in:1,0'],
        ]);

        $userIds = $request->input('user_ids');
        $useDefaultPassword = $request->input('use_default_password');

        $export = new ResetPasswordUser($userIds, $useDefaultPassword);
        $timeNow = date("Ymdhis");

        return Excel::download($export, "reset-password-user-$timeNow.xlsx");

    }

    public function importUser(Request $request) {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt'],
            'role' => ['required', 'in:'.role()::ROLE_LECTURE.','.role()::ROLE_STUDENT.','.role()::ROLE_ADMIN.','.role()::ROLE_SUPERADMIN],
            'use_default_password' => ['in:1,0']
        ]);

        $file = $request->file('file');

        DB::beginTransaction();
        try {

            $currentUser = Auth::user();

            $file = $request->file('file');
            $role = $request->role;

            // Upload to S3
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $name = $originalName . '-' . $role . '-' . $currentUser->id . '-' . time()  . '.' . $file->getClientOriginalExtension();
            $path = Storage::disk('s3')->putFileAs('import', $file, $name);;

            $fileLog = new FileLog();
            $fileLog->user_id = $currentUser->id;
            $fileLog->file_name = $name;
            $fileLog->file_path = $path;
            $fileLog->disk = 's3';
            $fileLog->import_type = $role;
            $fileLog->use_default_password = $request->use_default_password ?? false;
            $fileLog->save();

            ImportUserJob::dispatch($fileLog->id);

            DB::commit();
            return redirect()->back()->withSuccess('Import user success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withError($e->getMessage());
        }
    }
}
