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
        $lectures = User::role(role()::ROLE_LECTURE)
            ->select('users.*')
            ->get();

        $currentStudents = ActivityParticipant::with(['user'])
            ->where('activity_id', '=', $activity->id)
            ->where('is_lecturer', '=', false)
            ->get();

        $currentLecture = ActivityParticipant::where('activity_id', '=', $activity->id)
            ->where('is_lecturer', '=', true)
            ->first();

        return view('admin.activities.create', [
            'lectures' => $lectures,
            'currentStudents' => $currentStudents,
            'currentLecture' => $currentLecture
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
            'lectures' => ['array'],
            'lectures.*' => ['exists:users,id'],
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
            $activity->status = $request->status;
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

            if($request->has('lectures')) {
                foreach ($request->lectures as $lecture) {
                    $participant = new ActivityParticipant();
                    $participant->activity_id = $activity->id;
                    $participant->user_id = $lecture;
                    $participant->is_lecturer = true;
                    $participant->save();
                }
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

    public function edit(Request $request, Activity $activity ) {
        $lectures = User::role(role()::ROLE_LECTURE)
            ->select('users.*')
            ->get();

        $currentStudents = ActivityParticipant::with(['user'])
            ->where('activity_id', '=', $activity->id)
            ->where('is_lecturer', '=', false)
            ->get();

        $currentLecture = ActivityParticipant::where('activity_id', '=', $activity->id)
            ->where('is_lecturer', '=', true)
            ->first();

        return view('admin.activities.edit', [
            'activity' => $activity,
            'lectures' => $lectures,
            'currentStudents' => $currentStudents,
            'currentLecture' => $currentLecture,
        ]);
    }

    public function update(Request $request, Activity $activity) {
        $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'banner' => ['image', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        DB::beginTransaction();

        try {
            $activity->name = $request->name;
            $activity->description = $request->description;
            $activity->location = $request->location;
            $activity->start_date = $request->start_date;
            $activity->end_date = $request->end_date;
            $activity->status = $request->status;
            $activity->save();

            if ($request->hasFile('banner')) {
                $activity->clearMediaCollection('banner');
                $activity->addMediaFromRequest('banner')->toMediaCollection('banner');
            }

            DB::commit();

            return redirect()->route('admin.activities.show', $activity->id)->with([
                'success' => 'Activity updated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function addParticipant(Request $request, Activity $activity) {
        $request->validate([
            'lecture' => ['exists:users,id', 'nullable'],
            'lectures' => ['array'],
            'lectures.*' => ['exists:users,id'],
            'students' => ['array'],
            'students.*' => ['exists:users,id'],
        ]);

        DB::beginTransaction();
        try {
            if (!is_null($request->lecture)) {
                // Delete old lecture
                ActivityParticipant::where('activity_id', '=', $activity->id)
                    ->where('is_lecturer', '=', true)
                    ->delete();

                $participant = new ActivityParticipant();
                $participant->activity_id = $activity->id;
                $participant->user_id = $request->lecture;
                $participant->is_lecturer = true;
                $participant->save();
            }

            if($request->has('students')) {
                foreach ($request->students as $student) {
                    $participant = new ActivityParticipant();
                    $participant->activity_id = $activity->id;
                    $participant->user_id = $student;
                    $participant->is_lecturer = false;
                    $participant->save();
                }
            }

            if($request->has('lectures')) {
                foreach ($request->lectures as $lecture) {
                    $participant = new ActivityParticipant();
                    $participant->activity_id = $activity->id;
                    $participant->user_id = $lecture;
                    $participant->is_lecturer = true;
                    $participant->save();
                }
            }

            DB::commit();
            return redirect()->route('admin.activities.edit', $activity->id)->with([
                'success' => 'Participant added successfully',
            ]);
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with([
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function removeParticipant(Request $request, Activity $activity) {
        $request->validate([
            'participant' => ['exists:activity_participants,id'],
        ]);

        $participant = ActivityParticipant::find($request->participant);
        $participant->delete();

        return redirect()->route('admin.activities.update', $activity->id)->with([
            'success' => 'Participant removed successfully',
        ]);
    }

    public function delete(Request $request, Activity $activity) {
        $activity->delete();

        return redirect()->route('admin.activities.index')->with([
            'success' => 'Activity deleted successfully',
        ]);
    }
}
