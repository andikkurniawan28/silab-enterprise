<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($option) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Option '{$option->name}' was created.",
            ]);
        });

        static::updated(function ($option) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Option '{$option->name}' was updated.",
            ]);
        });

        static::deleted(function ($option) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Option '{$option->name}' was deleted.",
            ]);
        });
    }
}
