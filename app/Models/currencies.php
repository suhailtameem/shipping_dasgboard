<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class currencies extends Model
{
    use HasFactory;
    protected $fillable = [
        'currency',
        'currency_ar',
        'usdRate',
    ];

    public function expenses()
    {
        return $this->hasMany(ShipmentExpense::class, 'currency', 'currency');
    }

    public function shippingRates()
    {
        return $this->hasMany(shippingRates::class, 'currency', 'currency');
    }
}
