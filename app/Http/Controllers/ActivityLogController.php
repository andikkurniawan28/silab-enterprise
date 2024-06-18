<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\ActivityLog;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $from_datetime = session('from_datetime', now()->startOfDay()->format('Y-m-d 06:00:00'));
        $to_datetime = session('to_datetime', now()->endOfDay()->addDay()->format('Y-m-d 06:00:00'));
        if ($request->ajax()) {

            $data = ActivityLog::with('user')
                ->whereBetween('created_at', [$from_datetime, $to_datetime])
                ->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('user_id', function($row) {
                    return $row->user ? $row->user->name : 'N/A'; // Replace user_id with user name
                })
                ->editColumn('created_at', function($row) {
                    return $row->created_at->format('Y-m-d H:i:s'); // Format created_at
                })
                ->make(true);
        }
        $setup = Setup::init();
        return view('activity_log.index', compact('setup'));
    }
}
