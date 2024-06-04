<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setup = Setup::init();
        $setups = Setup::get()->last();
        return view("setup.index", compact("setup", "setups"));
    }

    public function update(Request $request, $id)
    {
        Setup::whereId($id)->update($request->except(["_token", "_method"]));
        ActivityLog::insert(["user_id" => Auth()->user()->id, "description" => "Setup was updated."]);
        return redirect()->back()->with("success", "Setup has been updated");
    }
}
