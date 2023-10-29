<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Activity;

use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\UserResource;

use App\Jobs\SendNotification;

use Log;

use App\Models\ReferenceStatus;

class AppointmentController extends Controller
{
    //
    public function index(Request $request) {

        // Validate input request
        $rules = [
            'order_by' => 'in:id,title,description,location,start_date,end_date,student_id,lecture_id,lecture2_id,status',
            'order_type' => 'in:asc,desc',
            'limit' => 'integer',
            'status' => 'in:'.ReferenceStatus::STATUS_APPOINTMENT_PENDING_ID.','.
                        ReferenceStatus::STATUS_APPOINTMENT_ACCEPTED_ID.','.
                        ReferenceStatus::STATUS_APPOINTMENT_REJECTED_ID . ','.
                        ReferenceStatus::STATUS_APPOINTMENT_CANCELED_ID. ','.
                        ReferenceStatus::STATUS_APPOINTMENT_DONE_ID,
            'start_date' => 'date',
            'end_date' => 'date',
            'filter_now' => ['boolean', 'nullable'],
        ];

        $messages = [
            'order_by.in' => 'Kolom pengurutan tidak valid',
            'order_type.in' => 'Tipe pengurutan tidak valid',
            'limit.integer' => 'Limit harus berupa angka',
            'status.in' => 'Status tidak valid',
            'start_date.date' => 'Tanggal mulai tidak valid',
            'end_date.date' => 'Tanggal selesai tidak valid',
            'filter_now.boolean' => 'Filter sekarang tidak valid',
        ];

        $this->validate($request, $rules, $messages);


        $user = $request->user();
        $limit = $request->limit ?? 10;

        $appointments = Appointment::orderBy('id', 'desc');
        // Validate activity is still active
        $appointments = $appointments->whereHas('activity', function($query) {
            $query->where('status', referenceStatus()::STATUS_ACTIVE)->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'));
        });

        // Check user role
        if ($user->hasRole(role()::ROLE_STUDENT)) {
            $appointments = $appointments->where('student_id', $user->id);
        } else if ($user->hasRole(role()::ROLE_LECTURE)) {
            $appointments = $appointments->where('lecture_id', $user->id);
        }

        if ($request->start_date) {
            $appointments = $appointments->whereDate('start_date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $appointments = $appointments->whereDate('end_date', '<=', $request->end_date);
        }

        if ($request->filter_now) {
            // Filter start_date today
            $appointments = $appointments->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'));
        }

        if ($request->order_by) {
            $orderType = $request->order_type ?? 'asc';
            $appointments = $appointments->orderBy($request->order_by, $orderType);
        }

        if ($request->status) {
            $appointments = $appointments->where('status', $request->status);
        }

        $appointments = $appointments->paginate($limit);

        $collection = AppointmentResource::collection($appointments);

        $meta = [
            'total' => $appointments->total(),
            'current_page' => $appointments->currentPage(),
            'per_page' => $appointments->perPage(),
            'last_page' => $appointments->lastPage()
        ];

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $collection,
            'meta' => $meta,
        ], 200);

    }

    public function show(Request $request, Appointment $appointment) {
        $user = $request->user();

        $activity = $appointment->activity;

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $appointment->load(['student', 'lecture', 'lecture2']);

        $appointmentResource = new AppointmentResource($appointment);
        $activityResource = new ActivityResource($activity);
        $studentResource = new UserResource($appointment->student);
        $lectureResource = new UserResource($appointment->lecture);
        if($appointment->lecture2) {
            $lecture2Resource = new UserResource($appointment->lecture2);
        } else {
            $lecture2Resource = null;
        }

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => [
                'appointment' => $appointmentResource,
                'activity' => $activityResource,
                'participant' => [
                    'student' => $studentResource,
                    'lecture' => $lectureResource,
                    'lecture2' => $lecture2Resource,
                ]
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
            'lecture_ids' => ['array', 'min:1', 'max:1'],
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
            'lecture_ids.max' => 'Pilih hanya 1 dosen',
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
            $appointment->lecture2_id = null;
        }
        $appointment->activity_id = $activity->id;
        $appointment->save();

        // Send Notification to lecture
        $title = 'Anda mendapatkan permintaan bimbingan';
        $body = 'Anda mendapatkan permintaan bimbingan dari ' . $user->name;
        $payload = [
            'appointment_id' => $appointment->id,
            'activity_id' => $activity->id,
            'type' => 'appointment',
        ];
        SendNotification::dispatch($appointment->lecture_id, true, $title, $body, $payload);

        return response()->json([
            'message' => 'Berhasil menambahkan data',
            'data' => new AppointmentResource($appointment),
        ], 201);
    }

    public function updateStatus(Request $request, Appointment $appointment) {
        $user = $request->user();

        $activity = $appointment->activity;

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $rules = [
            'status' => ['required', 'in:'.ReferenceStatus::STATUS_APPOINTMENT_ACCEPTED_ID.','.
                        ReferenceStatus::STATUS_APPOINTMENT_REJECTED_ID . ','.
                        ReferenceStatus::STATUS_APPOINTMENT_CANCELED_ID. ','.
                        ReferenceStatus::STATUS_APPOINTMENT_DONE_ID]
        ];

        $messages = [
            'status.required' => 'Status tidak boleh kosong',
            'status.in' => 'Status tidak valid',
        ];

        $this->validate($request, $rules, $messages);

        $availableUpdate = [];

        if($appointment->status == ReferenceStatus::STATUS_APPOINTMENT_PENDING_ID) {
            $availableUpdate = [
                ReferenceStatus::STATUS_APPOINTMENT_ACCEPTED_ID,
                ReferenceStatus::STATUS_APPOINTMENT_REJECTED_ID,
                ReferenceStatus::STATUS_APPOINTMENT_CANCELED_ID
            ];
        }

        if($appointment->status == ReferenceStatus::STATUS_APPOINTMENT_ACCEPTED_ID) {
            $availableUpdate = [
                ReferenceStatus::STATUS_APPOINTMENT_DONE_ID
            ];
        }

        if (!in_array($request->status, $availableUpdate)) {
            return response()->json([
                'message' => 'Status tidak dapat diubah ke status yang diminta',
            ], 400);
        }

        $appointment->status = $request->status;
        $appointment->save();

        // Send Notification to student
        $title = 'Status bimbingan telah diubah';
        $body = 'Status bimbingan dengan judul '.$appointment->title.' telah diubah menjadi ' . referenceStatus()::translateStatus($request->status);
        $payload = [
            'appointment_id' => $appointment->id,
            'activity_id' => $activity->id,
            'type' => 'appointment',
        ];
        SendNotification::dispatch($appointment->student_id, true, $title, $body, $payload);

        return response()->json([
            'message' => 'Berhasil mengubah status'
        ], 200);
    }
}
