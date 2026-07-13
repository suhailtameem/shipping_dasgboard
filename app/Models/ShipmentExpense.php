<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'expense_type_id',
        'amount',
        'created_by',
        'notes',
    ];

    public function shipment()
    {
        return $this->belongsTo(ShippingRequest::class, 'shipment_id');
    }

    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }
}
