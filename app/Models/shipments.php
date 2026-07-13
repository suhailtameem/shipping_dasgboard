<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shipments extends Model
{
    use HasFactory;
    protected $fillable = [
        'container',
        'from',
        'to',
        'progress',
        'pauto',
        'sh_type',
        'Created_by',
        'updated_by',
        'updated_at'
    ];
}
