<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\LogbookResource;

use App\Models\Activity;
use App\Models\ActivityLogbook;

use App\Jobs\SendNotification;

class LogbookController extends Controller
{
    //
    public function index(Activity $activity, Request $request) {
        $user = $request->user();

        $userId = $request->user_id ?? $user->id;
        if(!$user->hasRole('lecture')) {
            $userId = $user->id;
        }

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $user = $request->user();
        $limit = $request->limit ?? 10;

        $logbooks = ActivityLogbook::where('user_id', $userId)
            ->where('activity_id', $activity->id)
            ->orderBy('date', 'desc')
            ->paginate($limit);

        $collection = LogbookResource::collection($logbooks);

        $meta = [
            'total' => $logbooks->total(),
            'current_page' => $logbooks->currentPage(),
            'per_page' => $logbooks->perPage(),
            'last_page' => $logbooks->lastPage()
        ];

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $collection,
            'meta' => $meta,
        ], 200);
    }

    public function indexByUserId(Activity $activity, Request $request) {
        // Validate
        $rules = [
            'student_id' => ['required', 'integer', 'exists:users,id']
        ];

        $user = $request->user();
        $studentId = $request->student_id;

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $user = $request->user();
        $limit = $request->limit ?? 10;

        $logbooks = ActivityLogbook::where('user_id', $studentId)
            ->where('activity_id', $activity->id)
            ->orderBy('date', 'desc')
            ->paginate($limit);

        $collection = LogbookResource::collection($logbooks);

        $meta = [
            'total' => $logbooks->total(),
            'current_page' => $logbooks->currentPage(),
            'per_page' => $logbooks->perPage(),
            'last_page' => $logbooks->lastPage()
        ];

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => $collection,
            'meta' => $meta,
        ], 200);
    }

    public function show(Activity $activity, ActivityLogbook $logbook, Request $request) {
        $user = $request->user();

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        return response()->json([
            'message' => 'Berhasil menampilkan data',
            'data' => new LogbookResource($logbook),
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
            'date' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'description' => ['required', 'string'],
            'problem' => ['string', 'nullable'],
            'logbook_proof' => ['string', 'nullable']
        ];

        $messages = [
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid',
            'description.required' => 'Deskripsi tidak boleh kosong',
            'description.string' => 'Deskripsi tidak valid',
            'problem.string' => 'Problem tidak valid',
            'logbook_proof.string' => 'Bukti logbook tidak valid',
        ];

        $this->validate($request, $rules, $messages);

        $logbook = new ActivityLogbook();
        $logbook->activity_id = $activity->id;
        $logbook->user_id = $user->id;
        $logbook->date = $request->date;
        $logbook->description = $request->description;
        $logbook->problem = $request->problem;
        $logbook->logbook_proof = $request->logbook_proof;
        $logbook->save();

        // Lecture
        $lecture = $activity->lectures->first();

        // Send Notification to lecture
        $title = 'Mahasiswa mengirimkan logbook baru';
        $body = "Mahasiswa $user->name mengirimkan logbook baru pada kegiatan $activity->name";
        $payload = [
            'activity_id' => $activity->id,
            'type' => 'logbook',
        ];
        SendNotification::dispatch($lecture->id, true, $title, $body, $payload);

        return response()->json([
            'message' => 'Berhasil menambahkan logbook',
            'data' => new LogbookResource($logbook),
        ], 201);
    }

    public function update(Activity $activity, ActivityLogbook $logbook, Request $request) {
        $user = $request->user();

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $rules = [];

        if($user->hasRole('student')) {
            $rules = [
                'date' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
                'description' => ['required', 'string'],
                'problem' => ['string', 'nullable'],
                'logbook_proof' => ['string', 'nullable']
            ];
        }

        // If user is lecture, add lecture_comment to rules
        if ($user->hasRole('lecture')) {
            $rules['lecture_comment'] = ['string', 'nullable'];
        }

        $messages = [];

        if($user->hasRole('student')) {
            $messages = [
                'date.required' => 'Tanggal tidak boleh kosong',
                'date.date' => 'Tanggal tidak valid',
                'description.required' => 'Deskripsi tidak boleh kosong',
                'description.string' => 'Deskripsi tidak valid',
                'problem.string' => 'Problem tidak valid',
                'logbook_proof.string' => 'Bukti logbook tidak valid',
            ];
        }

        // If user is lecture, add lecture_comment to messages
        if ($user->hasRole('lecture')) {
            $messages['lecture_comment.string'] = 'Komentar dosen tidak valid';
        }

        $this->validate($request, $rules, $messages);

        if($user->hasRole('student')) {
            $logbook->date = $request->date;
            $logbook->description = $request->description;
            $logbook->problem = $request->problem;
            $logbook->logbook_proof = $request->logbook_proof;
        }

        // Send Notification to lecture or students
        $target = $activity->lectures->first()->id;
        $title = 'Mahasiswa mengirimkan logbook baru';
        $body = "Mahasiswa $user->name mengirimkan logbook baru pada kegiatan $activity->name";
        $payload = [
            'activity_id' => $activity->id,
            'type' => 'logbook',
        ];

        // If user is lecture, add lecture_comment to logbook
        if ($user->hasRole('lecture')) {
            $target = $logbook->user_id;
            $title = 'Dosen mengirimkan komentar pada logbook';
            $body = "Dosen $user->name mengirimkan komentar pada logbook mahasiswa ".$logbook->user->name." pada kegiatan $activity->name";
            $logbook->lecture_comment = $request->lecture_comment;

        }

        SendNotification::dispatch($target, true, $title, $body, $payload);

        $logbook->save();

        return response()->json([
            'message' => 'Berhasil mengubah logbook',
            'data' => new LogbookResource($logbook),
        ], 200);
    }
}
