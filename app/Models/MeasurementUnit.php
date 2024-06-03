<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasurementUnit extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($measurement_unit) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Measurement Unit '{$measurement_unit->name}' was created.",
            ]);
        });

        static::updated(function ($measurement_unit) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Measurement Unit '{$measurement_unit->name}' was updated.",
            ]);
        });

        static::deleted(function ($measurement_unit) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Measurement Unit '{$measurement_unit->name}' was deleted.",
            ]);
        });
    }
}
