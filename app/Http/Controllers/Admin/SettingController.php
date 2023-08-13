<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    //
    public function index(Request $request) {
        $defaultPassword = Setting::getDefaultPassword();

        return view('admin.settings.index', [
            'userDefaultPassword' => $defaultPassword,
        ]);
    }

    public function update(Request $request, Setting $setting) {
        if ($setting->meta == Setting::USER_DEFAULT_PASSWORD) {
            $request->validate([
                'default_password' => ['required', 'string', 'min:8'],
            ]);

            $setting->value = $request->default_password;
            $setting->save();
        }

        return redirect()->route('admin.settings.index')->with([
            'success' => 'settings updated',
        ]);
    }
}
