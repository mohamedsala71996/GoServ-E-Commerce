<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'order',
    ];

    public function items()
    {
        return $this->hasMany(BannerItem::class);
    }
}
