<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;

class DashboardController extends Controller
{
    //
    public function index(Request $request) {
        $totalLecture = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '=', role()::ROLE_LECTURE)
            ->count();

        $totalStudent = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '=', role()::ROLE_STUDENT)
            ->count();

        // Total activity active by start date and end date
        $totalActivity = Activity::where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->count();

        return view('admin.dashboard', [
            'totalLecture' => $totalLecture,
            'totalStudent' => $totalStudent,
            'totalActivity' => $totalActivity,
        ]);
    }
}
