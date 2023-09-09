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

        $rules = [
            'title' => 'required',
            'date' => ['required','date'],
            'description' => 'required',
            'location' => 'required',
            'lecture_ids' => ['array', 'min:1'],
            'lecture_ids.*' => ['exists:users,id']
        ];

        $messages = [
            'title.required' => 'Judul tidak boleh kosong',
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'location.required' => 'Lokasi tidak boleh kosong',
            'lecture_ids.array' => 'Dosen tidak valid',
            'lecture_ids.min' => 'Pilih minimal 1 dosen',
            'lecture_ids.*.exists' => 'Dosen tidak valid',
        ];

        $this->validate($request, $rules, $messages);

        $appointment = new Appointment();
        $appointment->title = $request->title;
        $appointment->description = $request->description;
        $appointment->location = $request->location;
        $appointment->date = $request->date;
        $appointment->student_id = $user->id;

        // Check if lecture_ids is not empty
        if (count($request->lecture_ids) > 0) {
            $appointment->lecture_id = $request->lecture_ids[0];
            $appointment->lecture2_id = $request->lecture_ids[1] ?? null;
        }
        $appointment->activity_id = $activity->id;
        $appointment->save();

        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'data' => new AppointmentResource($appointment),
        ], 201);
    }
}
