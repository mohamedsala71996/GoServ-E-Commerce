<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Contact extends Model
{
    use HasFactory,HasTranslations;

    protected $translatable = ['contact_type'];

    protected $fillable = [
        'contact_type',
        'contact_value',
    ];
}
