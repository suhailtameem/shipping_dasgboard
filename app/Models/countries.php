<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class countries extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'arabic',
        'status',
        'currency_id',
    ];

    public function currency()
    {
        return $this->belongsTo(currencies::class, 'currency_id');
    }


}
