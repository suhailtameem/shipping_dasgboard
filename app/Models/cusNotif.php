<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cusNotif extends Model
{
    use HasFactory;
    protected $fillable = [
        'value',
        'desc',
        'title_en',
        'title_ar',
        'msg_en',
        'msg_ar',
    ];
}
