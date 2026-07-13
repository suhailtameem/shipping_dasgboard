<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    use HasFactory;

    protected $fillable = ['name_en', 'name_ar', 'is_active'];

    public function expenses()
    {
        return $this->hasMany(ShipmentExpense::class, 'expense_type_id');
    }
}
