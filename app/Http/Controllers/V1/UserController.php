<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function attachFcmToken(Request $request) {
        // validate
        $rules = [
            'fcm_token' => 'required',
        ];

        $message = [
            'fcm_token.required' => 'Token tidak boleh kosong',
        ];

        $this->validate($request, $rules, $messages);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json([
            'message' => 'Berhasil menyimpan token',
        ]);

    }
}
