<?php

namespace App\Models;

use App\Models\Analysis;
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

    public static function serve()
    {
        $analytics = self::all();

        foreach ($analytics as $analytic) {
            $parameter = str_replace(" ", "_", $analytic->parameter->name);

            switch ($analytic->method) {

                case "Latest":
                    $data = Analysis::where("material_id", $analytic->material_id)
                        ->whereNotNull($parameter)
                        ->latest()
                        ->value($parameter) ?? null;
                    break;

                case "Average":
                    $data = Analysis::where("material_id", $analytic->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->avg($parameter) ?? null;
                    break;

                case "Minimum":
                    $data = Analysis::where("material_id", $analytic->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->min($parameter) ?? null;
                    break;

                case "Maximum":
                    $data = Analysis::where("material_id", $analytic->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->max($parameter) ?? null;
                    break;

                case "Summary":
                    $data = Analysis::where("material_id", $analytic->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->sum($parameter) ?? null;
                    break;

                case "Count":
                    $data = Analysis::where("material_id", $analytic->material_id)
                        ->whereBetween("created_at", [session('from_datetime'), session('to_datetime')])
                        ->count() ?? null;
                    break;

                case "Trendline":
                    $from = session('from_datetime');
                    $to = session('to_datetime');
                    $data = Analysis::where("material_id", $analytic->material_id)
                        ->whereBetween("created_at", [$from, $to])
                        ->orderBy("created_at")
                        ->get(['created_at', $parameter])
                        ->map(function ($item) use ($parameter) {
                            return [
                                'date' => strtotime($item->created_at),
                                'value' => $item->$parameter
                            ];
                        })
                        ->toArray();
                    break;

                default:
                    $data = null;
                    break;
            }

            $analytic->data = $data;
        }

        return $analytics;
    }
}
