<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Article extends Model
{
    use HasFactory,HasTranslations;
    protected $translatable = ['title', 'content'];

    protected $fillable = [
        'title',
        'content',
        'photo',
    ];
}
