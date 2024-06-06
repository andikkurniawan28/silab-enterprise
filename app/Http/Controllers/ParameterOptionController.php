<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\Setup;
use App\Models\Option;
use App\Models\ParameterOption;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ParameterOptionController extends Controller
{
    public function index()
    {
        $setup = Setup::init();
        $parameters = Parameter::where("type", "Option")->get();
        return view('parameter_option.index', compact('setup', 'parameters'));
    }

    public function adjust($parameter_id)
    {
        $setup = Setup::init();
        $options = Option::all();
        $parameter_options = ParameterOption::where('parameter_id', $parameter_id)->get();
        $parameter = Parameter::whereId($parameter_id)->get()->last();
        return view('parameter_option.adjust', compact('setup', 'options', 'parameter_options', 'parameter'));
    }

    public function update(Request $request, $parameter_id)
    {
        $request->validate([
            'option_ids' => 'nullable|array',
            'option_ids.*' => 'exists:options,id',
        ]);

        DB::transaction(function () use ($request, $parameter_id) {
            ParameterOption::where('parameter_id', $parameter_id)->delete();
            if (!empty($request->option_ids)) {
                $parameter_options = array_map(function ($option_id) use ($parameter_id) {
                    return [
                        'parameter_id' => $parameter_id,
                        'option_id' => $option_id,
                    ];
                }, $request->option_ids);
                ParameterOption::insert($parameter_options);
            }
        });

        ActivityLog::insert([
            "user_id" => Auth::id(),
            "description" => "Parameter Option for parameter '{$request->parameter}' were updated.",
        ]);

        return redirect()->back()->with('success', 'Parameter Option has been updated');
    }
}
