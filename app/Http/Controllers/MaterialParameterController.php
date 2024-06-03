<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Setup;
use App\Models\Parameter;
use App\Models\MaterialParameter;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MaterialParameterController extends Controller
{
    public function index()
    {
        $setup = Setup::init();
        $materials = Material::all();
        return view('material_parameter.index', compact('setup', 'materials'));
    }

    public function adjust($material_id)
    {
        $setup = Setup::init();
        $parameters = Parameter::all();
        $material_parameters = MaterialParameter::where('material_id', $material_id)->get();
        $material = Material::whereId($material_id)->get()->last();
        return view('material_parameter.adjust', compact('setup', 'parameters', 'material_parameters', 'material'));
    }

    public function update(Request $request, $material_id)
    {
        $request->validate([
            'parameter_ids' => 'nullable|array',
            'parameter_ids.*' => 'exists:parameters,id',
        ]);

        DB::transaction(function () use ($request, $material_id) {
            MaterialParameter::where('material_id', $material_id)->delete();
            if (!empty($request->parameter_ids)) {
                $material_parameters = array_map(function ($parameter_id) use ($material_id) {
                    return [
                        'material_id' => $material_id,
                        'parameter_id' => $parameter_id,
                    ];
                }, $request->parameter_ids);
                MaterialParameter::insert($material_parameters);
            }
        });

        ActivityLog::insert([
            "user_id" => Auth::id(),
            "description" => "MaterialParameter for material '{$request->material}' were updated.",
        ]);

        return redirect()->back()->with('success', 'MaterialParameter has been updated');
    }
}
