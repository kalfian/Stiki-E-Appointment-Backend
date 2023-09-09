<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\ActivityCollection;
use App\Models\Activity;
use App\Models\ActivityParticipant;
use App\Models\ReferenceStatus;
use Validator;

class ActivityController extends Controller
{
    //
    public function index(Request $request) {
        $user = $request->user();
        $limit = $request->limit ?? 10;

        $activities = Activity::where('status', ReferenceStatus::STATUS_ACTIVE)
            ->with(['banner'])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->orderBy('id', 'desc');


        if ($user->hasRole(role()::ROLE_STUDENT)) {
            $activities = $activities->whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('is_lecturer', false);
            });
        } else if ($user->hasRole(role()::ROLE_LECTURE)) {
            $activities = $activities->whereHas('participants', function($query) use ($user) {
                $query->where('user_id', $user->id)->where('is_lecturer', true);
            });
        }

        $activities = $activities->paginate($limit);

        $collection = new ActivityCollection($activities);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $collection,
        ], 200);

    }

    public function show(Request $request, Activity $activity) {
        $user = $request->user();
        $activity->load('participants');

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => new ActivityResource($activity),
        ], 200);

    }
}
