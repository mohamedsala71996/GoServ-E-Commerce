<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BannerItem extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'banner_id',
        'title',
        'description',
        'link',
        'photo',
    ];

    public $translatable = [
        'title',
        'description',
    ];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
