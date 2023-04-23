<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class StudentController extends Controller
{
    //
    public function index(Request $request) {
        $students = User::all();
        return view('admin.students.index', compact('students'));
    }
}
