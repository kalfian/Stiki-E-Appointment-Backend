<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class LectureController extends Controller
{
    //
    public function index(Request $request) {
        $lectures = User::whereHas('roles', function ($q) {
            $q->where('roles.name', '=', role()::ROLE_LECTURE);
          })->get();
        return view('admin.lectures.index', compact('lectures'));
    }

    public function datatables(Request $request) {
        if ($request->ajax() || is_debug()) {
            $lectures = User::whereHas('roles', function ($q) {
                $q->where('roles.name', '=', role()::ROLE_LECTURE);
              });

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
