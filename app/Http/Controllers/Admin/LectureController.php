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
                    return "<button class='btn btn-sm btn-primary btn-block btn-edit-lecture' data-id='$lecture->id'>Edit</button>";
                })
                ->addColumn('checkbox', function ($item) {
                    return '<input type="checkbox" value="'.$item->id.'" name="user_ids[]" />';
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
    }

    public function edit(Request $request) {
        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $lecture = User::where('id', $request->input('id'))
            ->whereHas('roles', function ($query) {
                $query->where('name', role()::ROLE_LECTURE);
            })
            ->first();

        return view('admin.lectures.edit', [
            'lecture' => $lecture,
        ]);
    }

    public function update(Request $request) {
        $validate = [
            'id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'gender' => ['required', 'in:1,0'],
            'phone_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:1,0'],
        ];

        $request->validate($validate);

        $lecture = User::where('id', $request->input('id'))
            ->whereHas('roles', function ($query) {
                $query->where('name', role()::ROLE_LECTURE);
            })
            ->first();

        if ($lecture->identity != $request->identity) {
            $validate['identity'] = ['required', 'string', 'max:255', 'unique:users'];
            $request->validate($validate);
        }

        $lecture->name = $request->name;
        $lecture->email = $request->email;
        $lecture->identity = $request->identity;
        $lecture->gender = $request->gender;
        $lecture->phone_number = $request->phone_number;
        $lecture->active_status = $request->status;
        $lecture->save();

        return redirect()->route('admin.lectures.index')->with([
            'success' => 'Lecture updated successfully',
        ]);

    }
}
