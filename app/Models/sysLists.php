<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class sysLists extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'title_en',
        'title_ar',
    ];

    public function options()
    {
        return $this->hasMany(lists::class, 'lid');
    }
}
