<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class LectureController extends Controller
{
    //
    public function index(Request $request) {
        return view('admin.lectures.index');
    }

    public function datatables(Request $request) {
        if ($request->ajax() || is_debug()) {
            $lectures = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', '=', role()::ROLE_LECTURE)
                ->select('users.*');

            return datatables()->of($lectures)
                ->addColumn('action', function ($lecture) {
                    return "TODO";
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
