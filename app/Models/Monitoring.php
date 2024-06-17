<?php

namespace App\Models;

use App\Models\Monitoring;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitoring extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }

    protected static function booted()
    {
        static::created(function ($monitoring) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Monitoring '{$monitoring->material->name}' with ID '{$monitoring->id}' was created.",
            ]);
        });

        static::updated(function ($monitoring) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Monitoring '{$monitoring->material->name}' with ID '{$monitoring->id}' was updated.",
            ]);
        });

        static::deleted(function ($monitoring) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Monitoring '{$monitoring->material->name}' with ID '{$monitoring->id}' was deleted.",
            ]);
        });
    }

    public static function serve()
    {
        $monitorings = self::all();

        foreach ($monitorings as $monitoring) {
            $parameter = str_replace(" ", "_", $monitoring->parameter->name);

            switch ($monitoring->method) {

                case "Latest":
                    $data = Analysis::where("material_id", $monitoring->material_id)
                        ->whereNotNull($parameter)
                        ->latest()
                        ->value($parameter) ?? null;
                    break;

                case "Average":
                    $data = Analysis::where("material_id", $monitoring->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->avg($parameter) ?? null;
                    break;

                case "Minimum":
                    $data = Analysis::where("material_id", $monitoring->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->min($parameter) ?? null;
                    break;

                case "Maximum":
                    $data = Analysis::where("material_id", $monitoring->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->max($parameter) ?? null;
                    break;

                case "Summary":
                    $data = Analysis::where("material_id", $monitoring->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->sum($parameter) ?? null;
                    break;

                case "Count":
                    $data = Analysis::where("material_id", $monitoring->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->count() ?? null;
                    break;

                case "Trendline":
                    $from = session('from_datetime');
                    $to = session('to_datetime');
                    $data = Analysis::where("material_id", $monitoring->material_id)
                        ->whereBetween("created_at", [$from, $to])
                        ->whereNotNull($parameter)
                        ->orderBy("created_at")
                        ->select("created_at", $parameter)
                        ->get();
                    break;

                default:
                    $data = null;
                    break;
            }

            $monitoring->data = $data;
        }

        return $monitorings;
    }
}
