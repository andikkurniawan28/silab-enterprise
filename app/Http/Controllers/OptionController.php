<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Setup;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $options = Option::all();
        return view('option.index', compact('setup', 'options'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $setup = Setup::init();
        return view('option.create', compact('setup'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|unique:options",
        ]);
        Option::create($validated);
        return redirect()->back()->with("success", "Option has been created");
    }

    /**
     * Display the specified resource.
     */
    public function show(Option $option)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $setup = Setup::init();
        $option = Option::findOrFail($id);
        return view('option.edit', compact('setup', 'option'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $option = Option::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|unique:options,name,' . $option->id,
        ]);
        Option::findOrFail($id)->update($validated);
        return redirect()->back()->with("success", "Option has been updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Option::findOrFail($id)->delete();
        return redirect()->back()->with("success", "Option has been deleted");
    }
}
