<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class destAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        "did",
        "en",
        "ar",
        "map",
        "phone1",
        "phone2",
    ];
}
