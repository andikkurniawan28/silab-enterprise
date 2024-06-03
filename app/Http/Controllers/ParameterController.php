<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Models\MeasurementUnit;
use Illuminate\Support\Facades\DB;

class ParameterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $parameters = Parameter::all();
        return view('parameter.index', compact('setup', 'parameters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $measurement_units = MeasurementUnit::all();
        return view('parameter.create', compact('setup', 'measurement_units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "measurement_unit_id" => "required",
            "name" => "required|unique:parameters",
            "min" => "required|numeric",
            "max" => "required|numeric",
        ]);
        Parameter::create($validated);
        return redirect()->back()->with("success", "Parameter has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Parameter $parameter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $parameter = Parameter::findOrFail($id);
        $measurement_units = MeasurementUnit::all();
        return view('parameter.edit', compact('setup', 'parameter', 'measurement_units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $parameter = Parameter::findOrFail($id);
        $validated = $request->validate([
            "measurement_unit_id" => "required",
            'name' => 'required|unique:parameters,name,' . $parameter->id,
            "min" => "required|numeric",
            "max" => "required|numeric",
        ]);
        $parameter->update($validated);
        self::updateAnalysisColumn($parameter, $request);
        return redirect()->back()->with("success", "Parameter has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Parameter::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Parameter has been deleted");
    }

    public static function updateAnalysisColumn($parameter, $request)
    {
        $old_column_name = str_replace(' ', '_', $parameter->name);
        $new_column_name = $request->name;
        $rename_query = "ALTER TABLE analyses CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` FLOAT NULL";
        DB::statement($rename_query);
    }
}
