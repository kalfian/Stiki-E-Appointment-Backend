<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;

class ActivityController extends Controller
{
    //

    public function index(Request $request) {
        return view('admin.activities.index');
    }

    public function datatables(Request $request) {
        if ($request->ajax() || isDebug()) {
            $activities = Activity::select('activities.*');

            return datatables()->of($activities)
                ->addColumn('action', function ($lecture) {
                    return "
                    <a href='#' class='btn btn-sm btn-info btn-block'><i class='fas fa-info-circle'></i> Detail</a>
                    ";
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create(Request $request) {
        return view('admin.activities.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        $activity = new Activity();
        $activity->name = $request->name;
        $activity->description = $request->description;
        $activity->location = $request->location;
        $activity->start_date = $request->start_date;
        $activity->end_date = $request->end_date;
        $activity->save();

        if ($request->hasFile('banner')) {
            $activity->addMediaFromRequest('banner')->toMediaCollection('banner');
        }

        return redirect()->route('admin.activities.index')->with([
            'success' => 'Activity created successfully',
        ]);
    }
}
