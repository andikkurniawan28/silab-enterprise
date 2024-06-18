<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Company;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setup = Setup::init();
        if ($request->ajax()) {
            $data = Customer::with('company')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('company_id', function ($row) {
                    return $row->company ? $row->company->name : 'N/A';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('customer.edit', $row->id);
                    return '
                    <div class="btn-group" role="group" aria-label="Action Buttons">
                        <a href="' . $editUrl . '" class="btn btn-secondary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '" data-name="' . $row->name . '">Delete</button>
                    </div>
                ';
                })
                ->rawColumns(['action', 'company_id'])
                ->setRowAttr([
                    'data-searchable' => 'true'
                ])
                ->make(true);
        }
        return view('customer.index', compact('setup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        $materials = Material::all();
        $companys = Company::all();
        return view('customer.create', compact('setup', 'materials', 'companys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation_rules = [
            "company_id" => "required",
            "name" => "required",
        ];
        $validated = $request->validate($validation_rules);
        Customer::create($validated);
        return redirect()->back()->with("success", "Customer has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $customer = Customer::findOrFail($id);
        $companys = Company::all();
        return view('customer.edit', compact('setup', 'customer', 'companys'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $validation_rules = [
            "company_id" => "required",
            'username' => 'required|unique:customers,name,' . $customer->id,
        ];
        $validated = $request->validate($validation_rules);
        $customer->update($validated);
        return redirect()->back()->with("success", "Customer has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Customer::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Customer has been deleted");
    }
}
