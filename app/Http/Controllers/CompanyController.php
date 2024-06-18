<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Setup;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $companys = Company::all();
        return view('company.index', compact('setup', 'companys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('company.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:companies",
        ]);
        Company::create($validated);
        return redirect()->back()->with("success", "Company has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $company = Company::findOrFail($id);
        return view('company.edit', compact('setup', 'company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:companies,name,' . $company->id,
        ]);
        Company::findOrFail($id)->update($validated);
        return redirect()->back()->with("success", "Company has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Company::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Company has been deleted");
    }
}
