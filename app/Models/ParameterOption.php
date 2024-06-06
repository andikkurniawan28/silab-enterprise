<?php

namespace App\Models;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParameterOption extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
