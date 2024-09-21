<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AboutOurStore extends Model
{
    use HasFactory, HasTranslations;
    
    protected $table='about_our_store';
    public $translatable = ['description'];

    protected $fillable = [
        'description',
    ];}
