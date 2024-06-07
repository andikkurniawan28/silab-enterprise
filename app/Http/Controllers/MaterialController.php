<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Station;
use App\Models\Material;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Models\MaterialCategory;
use App\Models\MaterialParameter;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $materials = Material::all();
        return view('material.index', compact('setup', 'materials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $stations = Station::all();
        $material_categories = MaterialCategory::all();
        $parameters = Parameter::all();
        return view('material.create', compact('setup', 'stations', 'material_categories', 'parameters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "station_id" => "required",
            "material_category_id" => "required",
            "name" => "required|unique:materials",
        ]);

        $material = Material::create($validated);

        if ($request->has('parameter_ids')) {
            MaterialParameter::where("material_id", $material->id)->delete();
            $parameters = $request->input('parameter_ids');
            foreach ($parameters as $parameterId) {
                if (Parameter::where('id', $parameterId)->exists()) {
                    MaterialParameter::create([
                        'parameter_id' => $parameterId,
                        'material_id' => $material->id,
                    ]);
                }
            }
        }

        return redirect()->back()->with("success", "Material has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $material = Material::findOrFail($id);
        $stations = Station::all();
        $material_categories = MaterialCategory::all();
        $parameters = Parameter::all();
        $material_parameters = MaterialParameter::where("material_id", $id)->get();
        return view('material.edit', compact('setup', 'material', 'stations', 'material_categories', 'parameters', 'material_parameters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $validated = $request->validate([
            "station_id" => "required",
            "material_category_id" => "required",
            'name' => 'required|unique:materials,name,' . $material->id,
        ]);

        if ($request->has('parameter_ids')) {
            MaterialParameter::where("material_id", $id)->delete();
            $features = $request->input('parameter_ids');
            foreach ($features as $featureId) {
                if (Parameter::where('id', $featureId)->exists()) {
                    MaterialParameter::create([
                        'parameter_id' => $featureId,
                        'material_id' => $id,
                    ]);
                }
            }
        }

        $material->update($validated);
        return redirect()->back()->with("success", "Material has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Material::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Material has been deleted");
    }
}
