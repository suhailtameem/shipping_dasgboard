<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shippingRates extends Model
{
    use HasFactory;
    protected $fillable=[
        'shtype',
        'from',
        'to',
        'weight_from',
        'Weight_to',
        'unit',
        'price',
        'currency',
        'updated_by',
    ];

    public function currencyInfo()
    {
        return $this->belongsTo(currencies::class, 'currency', 'currency');
    }

    public function fromDestination()
    {
        return $this->belongsTo(shDestinations::class, 'from');
    }

    public function toDestination()
    {
        return $this->belongsTo(shDestinations::class, 'to');
    }
}
