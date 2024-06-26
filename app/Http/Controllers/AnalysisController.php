<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Analysis;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        $parameters = Parameter::all();
        $from_datetime = session('from_datetime', now()->startOfDay()->format('Y-m-d 06:00:00'));
        $to_datetime = session('to_datetime', now()->endOfDay()->addDay()->format('Y-m-d 06:00:00'));
        if ($request->ajax()) {
            $data = Analysis::with('material', 'customer')
                ->whereBetween('created_at', [$from_datetime, $to_datetime])
                ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('material_id', function ($row) {
                    return $row->material ? '<a href="' . route('result_by_material.index', $row->material_id) . '" target="_blank">' . $row->material->name . '</a>' : 'N/A';
                })
                ->editColumn('customer_id', function ($row) {
                    return $row->customer ? $row->customer->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('parameters', function ($row) use ($parameters) {
                    $parameter_values = '';
                    foreach ($parameters as $parameter) {
                        $parameter_name = str_replace(' ', '_', $parameter->name);
                        if($parameter->type == "Numeric")
                        {
                            if (!empty($row->$parameter_name)) {
                                $parameter_values .= '<li>' . $parameter->name . '<sub>(' . $parameter->measurement_unit->name . ')</sub> = ' . number_format($row->$parameter_name, $parameter->behind_decimal) . '</li>';
                            }
                        }
                        else if($parameter->type == "Option") {
                            $parameter_values .= '<li>' . $parameter->name . '<sub>(' . $parameter->measurement_unit->name . ')</sub> = ' . $row->$parameter_name . '</li>';
                        }
                    }
                    return $parameter_values ? '<ul>' . $parameter_values . '</ul>' : '';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('analysis.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->rawColumns(['parameters', 'action', 'material_id', 'customer_id'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }
        return view('analysis.index', compact('setup', 'parameters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $materials = Material::all();
        $parameters = Parameter::all();
        $customers = Customer::all();
        return view('analysis.create', compact('setup', 'materials', 'parameters', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $parameters = Parameter::all();
        $validation_rules = [
            "material_id" => "required",
            "customer_id" => "required",
            "batch" => "nullable",
        ];
        foreach ($parameters as $parameter) {
            $parameter_name = str_replace(' ', '_', $parameter->name);
            if($parameter->type == "Numeric"){
                $validation_rules[$parameter_name] = "nullable|numeric";
            } elseif($parameter->type == "Option") {
                $validation_rules[$parameter_name] = "nullable";
            }
        }
        $validated = $request->validate($validation_rules);
        Analysis::create($validated);
        return redirect()->back()->with("success", "Analysis has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Analysis $analysis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $analysis = Analysis::findOrFail($id);
        $materials = Material::all();
        $parameters = Parameter::all();
        $customers = Customer::all();
        return view('analysis.edit', compact('setup', 'analysis', 'materials', 'parameters', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $analysis = Analysis::findOrFail($id);
        $parameters = Parameter::all();
        $validation_rules = [
            "material_id" => "required",
            "customer_id" => "required",
            "batch" => "nullable",
        ];
        foreach ($parameters as $parameter) {
            $parameter_name = str_replace(' ', '_', $parameter->name);
            if($parameter->type == "Numeric"){
                $validation_rules[$parameter_name] = "nullable|numeric";
            } elseif($parameter->type == "Option") {
                $validation_rules[$parameter_name] = "nullable";
            }
        }
        $validated = $request->validate($validation_rules);
        $analysis->update($validated);
        return redirect()->back()->with("success", "Analysis has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Analysis::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Analysis has been deleted");
    }
}
