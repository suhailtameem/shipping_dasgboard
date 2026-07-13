<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shDestinations extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'destinations',
        'ar',
        'status',
    ];
}
