<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }

    protected static function booted()
    {
        static::created(function ($company) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Company '{$company->name}' was created.",
            ]);
        });

        static::updated(function ($company) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Company '{$company->name}' was updated.",
            ]);
        });

        static::deleted(function ($company) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Company '{$company->name}' was deleted.",
            ]);
        });
    }
}
