<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->name}' was created.",
            ]);
        });

        static::updated(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->name}' was updated.",
            ]);
        });

        static::deleted(function ($material) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Material '{$material->name}' was deleted.",
            ]);
        });
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function material_category()
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    public function material_parameter()
    {
        return $this->hasMany(MaterialParameter::class);
    }

    public function analysis()
    {
        return $this->hasMany(Analysis::class);
    }
}
