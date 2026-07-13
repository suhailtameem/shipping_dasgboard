<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class packages extends Model
{
    use HasFactory;
    protected $fillable=[
        'rid',
        'name',
        'description',
        'ptype',
        'weight',
        'price',
    ];

    public function shipment()
    {
        return $this->belongsTo(ShippingRequest::class, 'rid');
    }

    public function packageType()
    {
        return $this->belongsTo(lists::class, 'ptype', 'value')->where('lid', 5);
    }
}
