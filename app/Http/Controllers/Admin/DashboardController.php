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
        $totalLecture = User::role(role()::ROLE_LECTURE)
            ->count();

        $totalStudent = User::role(role()::ROLE_STUDENT)
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
