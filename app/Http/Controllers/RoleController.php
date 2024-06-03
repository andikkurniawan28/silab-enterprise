<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setup;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $roles = Role::all();
        return view('role.index', compact('setup', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('role.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:roles",
        ]);
        Role::create($validated);
        return redirect()->back()->with("success", "Role has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $role = Role::findOrFail($id);
        return view('role.edit', compact('setup', 'role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);
        Role::findOrFail($id)->update($validated);
        return redirect()->back()->with("success", "Role has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Role has been deleted");
    }
}
