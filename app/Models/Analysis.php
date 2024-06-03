<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analysis extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($analysis) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Analysis '{$analysis->material->name}' with ID '{$analysis->id}' was created.",
            ]);
        });

        static::updated(function ($analysis) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Analysis '{$analysis->material->name}' with ID '{$analysis->id}' was updated.",
            ]);
        });

        static::deleted(function ($analysis) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Analysis '{$analysis->material->name}' with ID '{$analysis->id}' was deleted.",
            ]);
        });
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

}
