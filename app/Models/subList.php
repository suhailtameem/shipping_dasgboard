<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subList extends Model
{
    use HasFactory;

    protected $table = 'sub_lists';

    protected $fillable = [
        'list_id',
        'value',
        'en',
        'ar',
        'price',
        'img',
    ];

    public function parentList()
    {
        return $this->belongsTo(lists::class, 'list_id');
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
