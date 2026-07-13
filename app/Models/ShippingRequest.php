<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'cid',
        'rid',
        'getway',
        'getway_type',
        'tno',
        'shid',
        'sh_type',
        'containerized',
        'delivered_at',
        'from',
        'to',
        'clearnce',
        'situation',
        'req_status',
        'Comment',
        'updated_by',
        'step',
        
        'total_weight',
        'total_price',
        'currency_id',
    ];

    public function customer()
    {
        return $this->belongsTo(customers::class, 'cid');
    }

    public function orderCurrency()
    {
        return $this->belongsTo(currencies::class, 'currency_id');
    }

    public function receiver()
    {
        return $this->belongsTo(receiver::class, 'rid');
    }

    public function senderCountry()
    {
        return $this->belongsTo(countries::class, 'from', 'id');
    }

    public function receiverCountry()
    {
        return $this->belongsTo(countries::class, 'to', 'id');
    }

    public function packages()
    {
        return $this->hasMany(packages::class, 'rid');
    }

    public function fromDest()
    {
        return $this->belongsTo(shDestinations::class, 'from');
    }

    public function toDest()
    {
        return $this->belongsTo(shDestinations::class, 'to');
    }

    public function shippingType()
    {
        return $this->belongsTo(lists::class, 'sh_type', 'value')->where('lid', 1);
    }

    public function containerType()
    {
        return $this->belongsTo(lists::class, 'containerized', 'value')->where('lid', 3);
    }

    public function serviceType()
    {
        return $this->belongsTo(lists::class, 'clearnce', 'value')->where('lid', 4);
    }

    public function status()
    {
        return $this->belongsTo(lists::class, 'req_status', 'value')->where('lid', 2);
    }

    public function expenses()
    {
        return $this->hasMany(ShipmentExpense::class, 'shipment_id');
    }

    public function shipmentServices()
    {
        return $this->hasMany(ShipmentService::class, 'shipment_id');
    }
}
