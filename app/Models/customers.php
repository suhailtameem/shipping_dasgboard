<?php

namespace App\Models;
// use App\Models\customers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    use HasFactory;
    protected $fillable = [
        'first',
        'last',
        'email',
        'phone',
        'phone2',
        'country',
        'address',
        'location',
        'id_proff_image',
        'password',
        'type',
        'ws',
        'last_login',
        'use',
        'token',
        'lang',
        'legals',
    ];

    protected $hidden = ['password'];

    public function receivers()
    {
        return $this->hasMany(receiver::class, 'cid');
    }

    public function requests()
    {
        return $this->hasMany(ShippingRequest::class, 'cid');
    }

    public function country()
    {
        return $this->belongsTo(countries::class, 'country', 'id');
    }

    public function senderCountry()
    {
        return $this->belongsTo(countries::class, 'country', 'id');
    }
}
