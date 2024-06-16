<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($report_type) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Report Type '{$report_type->name}' was created.",
            ]);
        });

        static::updated(function ($report_type) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Report Type '{$report_type->name}' was updated.",
            ]);
        });

        static::deleted(function ($report_type) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Report Type '{$report_type->name}' was deleted.",
            ]);
        });
    }

    public function report_type_content(){
        return $this->hasMany(ReportTypeContent::class);
    }
}
