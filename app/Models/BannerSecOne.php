<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BannerSecOne extends Model
{
    use HasFactory,HasTranslations;

    protected $table = 'banners_sec_one';
    protected $fillable = ['title', 'description', 'photo', 'link'];
    protected $translatable = ['title', 'description'];
}
