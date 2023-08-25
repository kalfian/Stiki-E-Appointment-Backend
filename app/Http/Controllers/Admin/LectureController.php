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
        if ($request->ajax() || isDebug()) {
            $lectures = User::role(role()::ROLE_LECTURE)
                ->select('users.*');

            return datatables()->of($lectures)
                ->addColumn('action', function ($lecture) {
                    return "
                    <a href='".route('admin.lectures.show', $lecture->id)."')' class='btn btn-sm btn-info btn-block btn-detail-lecture'><i class='fas fa-info-circle'></i> Detail</a>
                    <a href='".route('admin.lectures.edit', $lecture->id)."')' class='btn btn-sm btn-primary btn-block btn-edit-lecture'><i class='fas fa-edit'></i> Edit</a>
                    ";
                })
                ->addColumn('checkbox', function ($item) {
                    return '<input type="checkbox" value="'.$item->id.'" name="user_ids[]" />';
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'checkbox'])
                ->make(true);
        }
    }

    public function edit(Request $request, User $lecture) {

        if (!$lecture->hasRole(role()::ROLE_LECTURE)) {
            return redirect()->route('admin.lectures.index')->with([
                'error' => 'Lecture not found',
            ]);
        }

        return view('admin.lectures.edit', [
            'lecture' => $lecture,
        ]);
    }

    public function show(Request $request, User $lecture) {
        if (!$lecture->hasRole(role()::ROLE_LECTURE)) {
            return redirect()->route('admin.lectures.index')->with([
                'error' => 'lecture not found',
            ]);
        }

        return view('admin.lectures.show', [
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

        $lecture = User::where('id', $request->input('id'))->first();
        if (!$lecture) {
            return redirect()->route('admin.lectures.index')->with([
                'error' => 'Lecture not found',
            ]);
        }

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

    public function create(Request $request) {
        return view('admin.lectures.create');
    }

    public function store(Request $request) {
        $validate = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'gender' => ['required', 'in:1,0'],
            'phone_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:1,0'],
            'identity' => ['required', 'string', 'max:255', 'unique:users'],
            'use_default_password' => ['required', 'in:1,0']
        ];

        $request->validate($validate);

        $lecture = new User();
        $lecture->name = $request->name;
        $lecture->email = $request->email;

        $password = passwordGenerator();
        if($request->use_default_password == 1) {
            $password = setting()::getDefaultPasswordValue();
        }

        $lecture->password = bcrypt($password);

        $lecture->identity = $request->identity;
        $lecture->gender = $request->gender;
        $lecture->phone_number = $request->phone_number;
        $lecture->active_status = $request->status;
        $lecture->save();

        $lecture->assignRole(role()::ROLE_LECTURE);

        return redirect()->route('admin.lectures.index')->with([
            'success' => 'Lecture created successfully',
        ]);
    }
}
