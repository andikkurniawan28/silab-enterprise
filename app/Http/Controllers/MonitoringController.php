<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Parameter;
use Yajra\DataTables\DataTables;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        $parameters = Parameter::all();
        if ($request->ajax()) {
            $data = Monitoring::with('material', 'parameter')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('material_id', function ($row) {
                    return $row->material ? '<a href="' . route('result_by_material.index', $row->material_id) . '" target="_blank">' . $row->material->name . '</a>' : 'N/A';
                })
                ->editColumn('parameter_id', function ($row) {
                    return $row->parameter ? $row->parameter->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('monitoring.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->rawColumns(['action', 'material_id', 'parameter_id'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }
        return view('monitoring.index', compact('setup', 'parameters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $materials = Material::all();
        $parameters = Parameter::all();
        return view('monitoring.create', compact('setup', 'materials', 'parameters'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation_rules = [
            "material_id" => "required",
            "parameter_id" => "required",
            "method" => "required",
        ];
        $validated = $request->validate($validation_rules);
        Monitoring::create($validated);
        return redirect()->back()->with("success", "Monitoring has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Monitoring $monitoring)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $monitoring = Monitoring::findOrFail($id);
        $materials = Material::all();
        $parameters = Parameter::all();
        return view('monitoring.edit', compact('setup', 'monitoring', 'materials', 'parameters'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $validation_rules = [
            "material_id" => "required",
            "parameter_id" => "required",
            "method" => "required",
        ];
        $validated = $request->validate($validation_rules);
        $monitoring->update($validated);
        return redirect()->back()->with("success", "Monitoring has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Monitoring::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Monitoring has been deleted");
    }
}
