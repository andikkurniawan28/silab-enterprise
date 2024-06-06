<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parameter extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($parameter) {
            if($parameter->type == "Numeric") {
                $type_data = "FLOAT";
            }
            else {
                $type_data = "VARCHAR(255)";
            }
            $column_name = str_replace(' ', '_', $parameter->name);
            $alter_query = "ALTER TABLE analyses ADD COLUMN `{$column_name}` {$type_data} NULL";
            DB::statement($alter_query);
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Parameter '{$parameter->name}' was created.",
            ]);
        });

        static::updated(function ($parameter) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Parameter '{$parameter->name}' was updated.",
            ]);
        });

        static::deleted(function ($parameter) {
            $column_name = str_replace(' ', '_', $parameter->name);
            $alter_query = "ALTER TABLE analyses DROP COLUMN `{$column_name}`";
            DB::statement($alter_query);
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Parameter '{$parameter->name}' was deleted.",
            ]);
        });
    }

    public function measurement_unit()
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function parameter_option()
    {
        return $this->hasMany(ParameterOption::class);
    }
}
