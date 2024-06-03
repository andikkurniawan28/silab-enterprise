<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Setup;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $stations = Station::all();
        return view('station.index', compact('setup', 'stations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('station.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:stations",
        ]);
        Station::create($validated);
        return redirect()->back()->with("success", "Station has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Station $station)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $station = Station::findOrFail($id);
        return view('station.edit', compact('setup', 'station'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $station = Station::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:stations,name,' . $station->id,
        ]);
        Station::findOrFail($id)->update($validated);
        return redirect()->back()->with("success", "Station has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Station::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Station has been deleted");
    }
}
