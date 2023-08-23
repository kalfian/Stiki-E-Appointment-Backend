<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\ActivityParticipant;

use App\Models\User;

use Illuminate\Support\Facades\DB;


class ActivityController extends Controller
{
    //

    public function index(Request $request) {
        return view('admin.activities.index');
    }

    public function datatables(Request $request) {
        if ($request->ajax() || isDebug()) {
            $activities = Activity::with(['banner'])->withCount(['students']);

            return datatables()->of($activities)
                ->addColumn('action', function ($activity) {
                    return "
                    <a href='".route('admin.activities.show', $activity->id)."' class='btn btn-sm btn-info btn-block'><i class='fas fa-info-circle'></i> Detail</a>
                    ";
                })
                ->addColumn('banner_image', function($activity){
                    $banner = "-";
                    if (!is_null($activity->banner)) {
                        $banner = "<a data-fancybox href='{$activity->banner->getUrl()}'><img src='{$activity->banner->getUrl('thumbnail')}' class='img-fluid img-200'></a>";
                    }

                    return $banner;
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'banner_image'])
                ->make(true);
        }
    }

    public function show(Request $request, Activity $activity) {
        // get participant order by is_lecturer true
        $participants = ActivityParticipant::where('activity_id', '=', $activity->id)
            ->orderBy('is_lecturer', 'desc')
            ->get();

        return view('admin.activities.show', [
            'activity' => $activity,
            'participants' => $participants,
        ]);
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
            'banner' => ['image', 'mimes:jpeg,png,jpg,gif,svg'],
            'lecture' => ['exists:users,id', 'nullable'],
            'students' => ['array'],
            'students.*' => ['exists:users,id'],
        ]);

        DB::beginTransaction();


        try {
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

            if (!is_null($request->lecture)) {
                $participant = new ActivityParticipant();
                $participant->activity_id = $activity->id;
                $participant->user_id = $request->lecture;
                $participant->is_lecturer = true;
                $participant->save();
            }

            if ($request->has('students')) {
                foreach ($request->students as $student) {
                    $participant = new ActivityParticipant();
                    $participant->activity_id = $activity->id;
                    $participant->user_id = $student;
                    $participant->is_lecturer = false;
                    $participant->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.activities.index')->with([
                'success' => 'Activity created successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
