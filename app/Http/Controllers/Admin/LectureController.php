<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class LectureController extends Controller
{
    //
    public function index(Request $request) {
        $lectures = User::all();
        return view('admin.lectures.index', compact('lectures'));
    }
}
