<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lists extends Model
{
    use HasFactory;

    protected $fillable = [
        'lid',
        'value',
        'en',
        'ar',
        'img',
        'has_sub',
    ];

    protected $casts = [
        'has_sub' => 'boolean',
    ];

    public function mainList()
    {
        return $this->belongsTo(sysLists::class, 'lid');
    }

    public function subLists()
    {
        return $this->hasMany(subList::class, 'list_id');
    }

    /**
     * Get the image URL, falling back to the default box image.
     */
    public function getImgUrlAttribute(): string
    {
        if ($this->img && file_exists(public_path('imgs/' . $this->img))) {
            return asset('imgs/' . $this->img);
        }
        return asset('imgs/box_def.jpg');
    }
}
