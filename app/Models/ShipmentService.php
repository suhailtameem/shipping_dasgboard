<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentService extends Model
{
    use HasFactory;

    protected $table = 'shipment_services';

    protected $fillable = [
        'shipment_id',
        'sub_list_id',
        'title_en',
        'title_ar',
        'price',
        'quantity',
    ];

    public function shipment()
    {
        return $this->belongsTo(ShippingRequest::class, 'shipment_id');
    }

    public function subListItem()
    {
        return $this->belongsTo(subList::class, 'sub_list_id');
    }

    /**
     * Calculated total (price × quantity).
     */
    public function getTotalAttribute(): float
    {
        return (float) $this->price * (int) $this->quantity;
    }
}
