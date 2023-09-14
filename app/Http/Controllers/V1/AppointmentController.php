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
    public function index(Request $request) {
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
            'start_date' => ['required','date', 'before:end_date', 'date_format:Y-m-d H:i:s'],
            'end_date' => ['required','date', 'after:start_date', 'date_format:Y-m-d H:i:s'],
            'description' => 'required',
            'location' => 'required',
            'lecture_ids' => ['array', 'min:1'],
            'lecture_ids.*' => ['exists:users,id']
        ];

        $messages = [
            'title.required' => 'Judul tidak boleh kosong',
            'start_date.required' => 'Tanggal mulai tidak boleh kosong',
            'start_date.date' => 'Tanggal mulai tidak valid',
            'start_date.before' => 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai',
            'start_date.date_format' => 'Format tanggal mulai tidak valid',
            'end_date.required' => 'Tanggal selesai tidak boleh kosong',
            'end_date.date' => 'Tanggal selesai tidak valid',
            'end_date.after' => 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai',
            'end_date.date_format' => 'Format tanggal selesai tidak valid',
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
        $appointment->start_date = $request->start_date;
        $appointment->end_date = $request->end_date;
        $appointment->student_id = $user->id;
        $appointment->status = referenceStatus()::STATUS_APPOINTMENT_PENDING_ID;

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
