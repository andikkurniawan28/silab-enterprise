<?php

namespace App\Models;

use App\Models\Monitoring;
use App\Models\ReportType;
use App\Models\MaterialCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setup extends Model
{
    use HasFactory;

    public static function init()
    {
        $setup = self::get()->last();
        $setup->permission = Permission::where("role_id", Auth()->user()->role_id)->with('feature')->get();
        $setup->stations = Station::all();
        $setup->material_categories = MaterialCategory::all();
        $setup->report_types = ReportType::all();
        $setup->monitorings = Monitoring::serve();
        return $setup;
    }
}
