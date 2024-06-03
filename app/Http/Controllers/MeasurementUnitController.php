<?php

namespace App\Http\Controllers;

use App\Models\MeasurementUnit;
use App\Models\Setup;
use Illuminate\Http\Request;

class MeasurementUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $measurement_units = MeasurementUnit::all();
        return view('measurement_unit.index', compact('setup', 'measurement_units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('measurement_unit.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:measurement_units",
        ]);
        MeasurementUnit::create($validated);
        return redirect()->back()->with("success", "Measurement Unit has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(MeasurementUnit $measurement_unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $measurement_unit = MeasurementUnit::findOrFail($id);
        return view('measurement_unit.edit', compact('setup', 'measurement_unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $measurement_unit = MeasurementUnit::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:measurement_units,name,' . $measurement_unit->id,
        ]);
        MeasurementUnit::findOrFail($id)->update($validated);
        return redirect()->back()->with("success", "Measurement Unit has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        MeasurementUnit::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Measurement Unit has been deleted");
    }
}
