<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ExchangeAndReturnPolicy extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'description',
        'status',
    ];

    public $translatable = ['description'];
}
