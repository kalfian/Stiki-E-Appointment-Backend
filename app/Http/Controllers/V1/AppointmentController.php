<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Activity;

use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\ActivityResource;

class AppointmentController extends Controller
{
    //
    public function index() {
        $user = $request->user();
        $limit = $request->limit ?? 10;

        $appointments = Appointment::orderBy('id', 'desc');

        // Check user role
        if ($user->hasRole(role()::ROLE_STUDENT)) {
            $appointments = $appointments->where('student_id', $user->id);
        } else if ($user->hasRole(role()::ROLE_LECTURE)) {
            $appointments = $appointments->where('lecture_id', $user->id);
        }

        $appointments = $appointments->paginate($limit);

        $collection = new AppointmentCollection($appointments);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $collection,
        ], 200);

    }

    public function show(Activity $activity, Appointment $appointment) {
        $user = $request->user();

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $appointment->load(['student', 'lecture']);

        $appointmentResource = new AppointmentResource($appointment);
        $activityResource = new ActivityResource($activity);

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => [
                'appointment' => $appointmentResource,
                'activity' => $activityResource,
            ],
        ], 200);
    }

    public function store(Activity $activity, Request $request) {
        $user = $request->user();

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $request->validate([
            'title' => 'required',
            'date' => ['required','date'],
            'description' => 'required',
            'lecture_id' => ['required', 'exists:users,id'],
            'lecture2_id' => ['nullable', 'exists:users,id']
        ]);

        $appointment = new Appointment();
        $appointment->title = $request->title;
        $appointment->description = $request->description;
        $appointment->date = $request->date;
        $appointment->student_id = $user->id;
        $appointment->lecture_id = $request->lecture_id;
        $appointment->lecture2_id = $request->lecture2_id;
        $appointment->activity_id = $activity->id;
        $appointment->save();

        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'data' => new AppointmentResource($appointment),
        ], 201);
    }
}
