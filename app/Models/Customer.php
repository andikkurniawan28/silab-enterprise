<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function company(){
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::created(function ($customer) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Customer '{$customer->name}' was created.",
            ]);
        });

        static::updated(function ($customer) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Customer '{$customer->name}' was updated.",
            ]);
        });

        static::deleted(function ($customer) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => "Customer '{$customer->name}' was deleted.",
            ]);
        });
    }
}
