<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Activity;

class ActivityController extends Controller
{
    //

    public function index(Request $request) {
        $activities = Activity::all();
        return view('admin.activities.index', compact('activities'));
    }

    public function datatables(Request $request) {
        if ($request->ajax() || isDebug()) {
            $activities = Activity::select('activities.*');

            return datatables()->of($activities)
                ->addColumn('action', function ($lecture) {
                    return "
                    <a href='#' class='btn btn-sm btn-info btn-block'><i class='fas fa-info-circle'></i> Detail</a>
                    ";
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
