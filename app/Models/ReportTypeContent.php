<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportTypeContent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function report_type(){
        return $this->belongsTo(ReportType::class);
    }

    public function material(){
        return $this->belongsTo(Material::class);
    }
}
