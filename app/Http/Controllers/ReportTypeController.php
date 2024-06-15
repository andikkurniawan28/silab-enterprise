<?php

namespace App\Http\Controllers;

use App\Models\ReportType;
use App\Models\Setup;
use App\Models\Material;
use App\Models\ReportTypeContent;
use Illuminate\Http\Request;

class ReportTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $report_types = ReportType::all();
        return view('report_type.index', compact('setup', 'report_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $materials = Material::all();
        return view('report_type.create', compact('setup', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:report_types",
        ]);

        $report_type = ReportType::create($validated);

        if ($request->has('material_ids')) {
            ReportTypeContent::where("report_type_id", $report_type->id)->delete();
            $materials = $request->input('material_ids');
            foreach ($materials as $materialId) {
                if (Material::where('id', $materialId)->exists()) {
                    ReportTypeContent::create([
                        'material_id' => $materialId,
                        'report_type_id' => $report_type->id,
                    ]);
                }
            }
        }

        return redirect()->back()->with("success", "Report Type has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(ReportType $report_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $report_type = ReportType::findOrFail($id);
        $materials = Material::all();
        $report_type_contents = ReportTypeContent::where('report_type_id', $id)->get();
        return view('report_type.edit', compact('setup', 'report_type', 'materials', 'report_type_contents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $report_type = ReportType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|unique:report_types,name,' . $report_type->id,
        ]);

        if ($request->has('material_ids')) {
            ReportTypeContent::where("report_type_id", $id)->delete();
            $materials = $request->input('material_ids');
            foreach ($materials as $materialId) {
                if (Material::where('id', $materialId)->exists()) {
                    ReportTypeContent::create([
                        'material_id' => $materialId,
                        'report_type_id' => $id,
                    ]);
                }
            }
        }

        $report_type->update($validated);
        return redirect()->back()->with("success", "Report Type has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ReportType::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Report Type has been deleted");
    }
}
