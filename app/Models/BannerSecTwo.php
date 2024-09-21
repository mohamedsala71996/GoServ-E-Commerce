<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BannerSecTwo extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'banner_sec_two';
    protected $fillable = ['title', 'description', 'photo', 'link'];
    protected $translatable = ['title', 'description'];
}
