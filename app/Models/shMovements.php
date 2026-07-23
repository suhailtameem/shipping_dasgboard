<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shMovements extends Model
{
    use HasFactory;
    protected $fillable =[
        'shid',
        'order',
        'step_date',
        'details',
        'location',
    ];

    public function shipment()
    {
        return $this->belongsTo(shipments::class, 'shid');
    }
}
