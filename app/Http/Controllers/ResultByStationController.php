<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Station;
use App\Models\Analysis;
use App\Models\Material;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Yajra\DataTables\Facades\DataTables;

class ResultByStationController extends Controller
{
    public function index($station_id)
    {
        $setup = Setup::init();
        $stations = Station::all();
        $station_selected = Station::with(['material.analysis' => function($query) {
            $from_datetime = session('from_datetime', now()->startOfDay()->format('Y-m-d 06:00:00'));
            $to_datetime = session('to_datetime', now()->endOfDay()->addDay()->format('Y-m-d 06:00:00'));
            $query->whereBetween('created_at', [$from_datetime, $to_datetime])->orderBy('id', 'desc');
        }])->findOrFail($station_id);
        return view('result_by_station.index', compact('setup', 'stations', 'station_selected'));
    }

    public function filter(Request $request)
    {
        AuthController::changeDatetime($request);
        return redirect()->route("result_by_station.index", $request->station);
    }
}
