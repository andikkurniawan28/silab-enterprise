<?php

namespace App\Http\Controllers;

use App\Models\Setup;
use App\Models\Analysis;
use App\Models\Material;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\MaterialParameter;
use Carbon\Carbon;

class ResultByMaterialController extends Controller
{
    public function index(Request $request, $material_id)
    {
        $setup = Setup::init();
        $material_parameters = MaterialParameter::where("material_id", $material_id)->get();
        $material = Material::whereId($material_id)->first(); // Menggunakan first() karena kita hanya ingin satu objek

        // Define session from_datetime dan to_datetime jika tidak ada
        if (!$request->session()->has('from_datetime') || !$request->session()->has('to_datetime')) {
            $from_datetime = Carbon::today()->addHours(6);
            $to_datetime = $from_datetime->copy()->addHours(24);
            $request->session()->put('from_datetime', $from_datetime);
            $request->session()->put('to_datetime', $to_datetime);
        } else {
            $from_datetime = Carbon::parse($request->session()->get('from_datetime'));
            $to_datetime = Carbon::parse($request->session()->get('to_datetime'));
        }

        if ($request->ajax()) {
            // Filter data berdasarkan from_datetime dan to_datetime
            $data = Analysis::with('material', 'customer')
                ->where('material_id', $material_id)
                ->whereBetween('created_at', [$from_datetime, $to_datetime])
                ->latest()
                ->get();

            // Membuat array untuk menampung data yang akan ditampilkan di tabel
            $formatted_data = [];
            foreach ($data as $row) {
                $formatted_row = [
                    'id' => $row->id,
                    'batch' => $row->batch,
                    'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                    'material_name' => $row->material->name,
                    'customer_name' => $row->customer->name,
                ];

                // Menambahkan data material parameter sebagai kolom tersendiri
                foreach ($material_parameters as $material_parameter) {
                    $parameter_name = str_replace(' ', '_', $material_parameter->parameter->name);
                    if($material_parameter->parameter->type == "Numeric"){
                        $formatted_row[$parameter_name] = !empty($row->$parameter_name) ? number_format($row->$parameter_name, $material_parameter->parameter->behind_decimal) : '';
                    }
                    elseif($material_parameter->parameter->type == "Option"){
                        $formatted_row[$parameter_name] = $row->$parameter_name;
                    }
                }

                $formatted_data[] = $formatted_row;
            }
            return DataTables::of($formatted_data)->make(true); // Menggunakan DataTables
        }
        return view('result_by_material.index', compact('setup', 'material_parameters', 'material'));
    }
}
