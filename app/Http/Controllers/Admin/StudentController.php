<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class StudentController extends Controller
{
    //
    public function index(Request $request) {
        return view('admin.students.index');
    }

    public function datatables(Request $request) {
        if ($request->ajax() || isDebug()) {
            $students = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', '=', role()::ROLE_STUDENT)
                ->select('users.*');

            return datatables()->of($students)
                ->addColumn('action', function ($student) {
                    return "
                    <a href='".route('admin.students.show', $student->id)."')' class='btn btn-sm btn-info btn-block btn-edit-student'><i class='fas fa-info-circle'></i> Detail</a>
                    <a href='".route('admin.students.edit', $student->id)."')' class='btn btn-sm btn-primary btn-block btn-edit-student'><i class='fas fa-edit'></i> Edit</a>
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

    public function show(Request $request, User $student) {
        if (!$student->hasRole(role()::ROLE_STUDENT)) {
            return redirect()->route('admin.students.index')->with([
                'error' => 'student not found',
            ]);
        }

        return view('admin.students.show', [
            'student' => $student,
        ]);
    }

    public function edit(Request $request, User $student) {

        if (!$student->hasRole(role()::ROLE_STUDENT)) {
            return redirect()->route('admin.students.index')->with([
                'error' => 'student not found',
            ]);
        }

        return view('admin.students.edit', [
            'student' => $student,
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
            'major' => ['required', 'string', 'max:255'],
        ];

        $request->validate($validate);

        $student = User::where('id', $request->input('id'))->first();
        if (!$student) {
            return redirect()->route('admin.students.index')->with([
                'error' => 'student not found',
            ]);
        }

        if ($student->identity != $request->identity) {
            $validate['identity'] = ['required', 'string', 'max:255', 'unique:users'];
            $request->validate($validate);
        }

        $student->name = $request->name;
        $student->email = $request->email;
        $student->identity = $request->identity;
        $student->gender = $request->gender;
        $student->phone_number = $request->phone_number;
        $student->major = $request->major;
        $student->active_status = $request->status;
        $student->save();

        return redirect()->route('admin.students.index')->with([
            'success' => 'student updated successfully',
        ]);
    }

    public function create(Request $request) {
        return view('admin.students.create');
    }

    public function store(Request $request) {
        $validate = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'gender' => ['required', 'in:1,0'],
            'phone_number' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:1,0'],
            'identity' => ['required', 'string', 'max:255', 'unique:users'],
            'major' => ['required', 'string', 'max:255'],
            'use_default_password' => ['required', 'in:1,0']
        ];

        $request->validate($validate);

        $student = new User();
        $student->name = $request->name;
        $student->email = $request->email;

        $password = passwordGenerator();
        if($request->use_default_password == 1) {
            $password = setting()::getDefaultPasswordValue();
        }

        $student->password = bcrypt($password);

        $student->identity = $request->identity;
        $student->gender = $request->gender;
        $student->phone_number = $request->phone_number;
        $student->active_status = $request->status;
        $student->major = $request->major;
        $student->save();

        $student->assignRole(role()::ROLE_STUDENT);

        return redirect()->route('admin.students.index')->with([
            'success' => 'student created successfully',
        ]);
    }

    public function select2(Request $request) {
        $students = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '=', role()::ROLE_STUDENT)
            ->select('users.*');

        if ($request->has('q')) {
            $students = $students->where(function($query) use ($request) {
                $query->where('users.name', 'like', "%{$request->q}%")
                    ->orWhere('users.identity', 'like', "%{$request->q}%");
            });
        }

        $students = $students->get();

        $data = [];
        foreach ($students as $student) {
            $data[] = [
                'id' => $student->id,
                'text' => "{$student->identity} - {$student->name}",
            ];
        }

        return response()->json($data);
    }
}
