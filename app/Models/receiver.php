<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'cid',
        'first',
        'last',
        'full',
        'phone',
        'phone2',
        'email',
        'country',
        'address',
        'prof_id_img',
        'verify_id',
    ];

    protected $casts = [
        'verify_id' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(customers::class, 'cid');
    }

    public function requests()
    {
        return $this->hasMany(ShippingRequest::class, 'rid');
    }

    public function country()
    {
        return $this->belongsTo(countries::class, 'country', 'id');
    }

    public function recCountry()
    {
        return $this->belongsTo(countries::class, 'country', 'id');
    }
}
