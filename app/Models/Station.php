<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Station extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function material()
    {
        return $this->hasMany(Material::class);
    }

    protected static function booted()
    {
        static::created(function ($station) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Station '{$station->name}' was created.",
            ]);
        });

        static::updated(function ($station) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Station '{$station->name}' was updated.",
            ]);
        });

        static::deleted(function ($station) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Station '{$station->name}' was deleted.",
            ]);
        });
    }
}
