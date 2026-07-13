<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'logo',
        'email',
        'phone',
        'website',
        'google_map_url',
    ];

    
}
