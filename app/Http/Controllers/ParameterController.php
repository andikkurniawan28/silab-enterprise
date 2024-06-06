<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Option;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Models\MeasurementUnit;
use App\Models\ParameterOption;
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
        $options = Option::all();
        return view('parameter.create', compact('setup', 'measurement_units', 'options'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "measurement_unit_id" => "required",
            "name" => "required|unique:parameters",
            "type" => "required",
            "min" => "nullable|numeric",
            "max" => "nullable|numeric",
        ]);

        $parameter = Parameter::create($validated);

        if ($request->has('option_ids')) {
            $options = $request->input('option_ids');
            foreach ($options as $optionId) {
                if (Option::where('id', $optionId)->exists()) {
                    ParameterOption::create([
                        'option_id' => $optionId,
                        'parameter_id' => $parameter->id,
                    ]);
                }
            }
        }

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
        $options = Option::all();
        $parameter_options = ParameterOption::where('parameter_id', $id)->get();
        return view('parameter.edit', compact('setup', 'parameter', 'measurement_units', 'options', 'parameter_options'));
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
            "type" => "required",
            "min" => "nullable|numeric",
            "max" => "nullable|numeric",
        ]);

        if ($request->has('option_ids')) {
            ParameterOption::where("parameter_id", $id)->delete();
            $options = $request->input('option_ids');
            foreach ($options as $optionId) {
                if (Option::where('id', $optionId)->exists()) {
                    ParameterOption::create([
                        'option_id' => $optionId,
                        'parameter_id' => $id,
                    ]);
                }
            }
        }

        self::updateAnalysisColumn($parameter, $request);

        $parameter->update($validated);

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
        if($request->type == "Numeric") {
            $type_data = "FLOAT";
        }
        else {
            $type_data = "VARCHAR(255)";
        }
        $old_column_name = str_replace(' ', '_', $parameter->name);
        $new_column_name = str_replace(' ', '_', $request->name);
        $rename_query = "ALTER TABLE analyses CHANGE COLUMN `{$old_column_name}` `{$new_column_name}` {$type_data} NULL";
        DB::statement($rename_query);
    }
}
