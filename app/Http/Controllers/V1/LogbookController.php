<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Resources\LogbookResource;
use App\Models\Activity;
use App\Models\ActivityLogbook;

class LogbookController extends Controller
{
    //
    public function index(Activity $activity, Request $request) {
        $user = $request->user();

        // Validate if user is participant
        $isParticipant = $activity->participants->where('user_id', $user->id)->first();
        if (!$isParticipant) {
            return response()->json([
                'message' => 'Anda tidak memiliki akses kegiatan ini',
            ], 401);
        }

        $user = $request->user();
        $limit = $request->limit ?? 10;

        $logbooks = ActivityLogbook::where('user_id', $user->id)
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

        return response()->json([
            'message' => 'Berhasil menambahkan logbook',
            'data' => new LogbookResource($logbook),
        ], 201);
    }
}
