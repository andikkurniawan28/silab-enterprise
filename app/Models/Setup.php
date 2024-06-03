<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    use HasFactory;

    public static function init()
    {
        $setup = self::get()->last();
        $setup->permission = Permission::where("role_id", Auth()->user()->role_id)->with('feature')->get();
        $setup->stations = Station::all();
        return $setup;
    }
}
