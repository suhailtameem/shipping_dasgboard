<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appSettings extends Model
{
    use HasFactory;
    protected $fillable =[
        "power",
        "power_en",
        "power_ar",
        "version",
        "old",
        "old_en",
        "old_ar",
        "link",
        "legals_en",
        "legals_ar",
        "cs"
    ];
}
