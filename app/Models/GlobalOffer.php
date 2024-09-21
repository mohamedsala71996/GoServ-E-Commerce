<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalOffer extends Model
{
    use HasFactory;
    protected $fillable = ['discount_percentage','start_time', 'end_time', 'is_active'];
    public function offers()
    {
        return $this->hasMany(Offer::class,'global_offer_id');
    }
}
