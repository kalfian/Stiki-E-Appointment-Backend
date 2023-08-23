<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\User;


class ActivityController extends Controller
{
    //

    public function index(Request $request) {
        return view('admin.activities.index');
    }

    public function datatables(Request $request) {
        if ($request->ajax() || isDebug()) {
            $activities = Activity::with(['banner']);

            return datatables()->of($activities)
                ->addColumn('action', function ($lecture) {
                    return "
                    <a href='#' class='btn btn-sm btn-info btn-block'><i class='fas fa-info-circle'></i> Detail</a>
                    ";
                })
                ->addColumn('total_participant', function($lecture){
                    return 0;
                })
                ->addColumn('banner_image', function($lecture){
                    return "
                    <a data-fancybox href='{$lecture->banner->getUrl()}'><img src='{$lecture->banner->getUrl('thumbnail')}' class='img-fluid img-200'></a>
                    ";
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'banner_image'])
                ->make(true);
        }
    }

    public function create(Request $request) {
        $lectures = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', '=', role()::ROLE_LECTURE)
            ->select('users.*')
            ->get();

        return view('admin.activities.create', [
            'lectures' => $lectures,
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'banner' => ['image', 'mimes:jpeg,png,jpg,gif,svg']
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
