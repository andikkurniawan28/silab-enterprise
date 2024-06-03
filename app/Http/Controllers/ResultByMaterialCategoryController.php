<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\MaterialCategory;
use App\Models\Analysis;
use App\Models\Material;
use App\Models\Parameter;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Yajra\DataTables\Facades\DataTables;

class ResultByMaterialCategoryController extends Controller
{
    public function index($material_category_id)
    {
        $setup = Setup::init();
        $material_categorys = MaterialCategory::all();
        $material_category_selected = MaterialCategory::with(['material.analysis' => function($query) {
            $from_datetime = session('from_datetime', now()->startOfDay()->format('Y-m-d 06:00:00'));
            $to_datetime = session('to_datetime', now()->endOfDay()->addDay()->format('Y-m-d 06:00:00'));
            $query->whereBetween('created_at', [$from_datetime, $to_datetime])->orderBy('id', 'desc');
        }])->findOrFail($material_category_id);
        return view('result_by_material_category.index', compact('setup', 'material_categorys', 'material_category_selected'));
    }

    public function filter(Request $request)
    {
        AuthController::changeDatetime($request);
        return redirect()->route("result_by_material_category.index", $request->material_category);
    }
}
