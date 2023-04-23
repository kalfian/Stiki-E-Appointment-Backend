<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;

class ActivityController extends Controller
{
    //

    public function index(Request $request) {
        $activities = Activity::all();
        return view('admin.activities.index', compact('activities'));
    }
}
