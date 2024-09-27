<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductNotification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','product_color_size_id', 'email', 'notified'];

    public function productColorSize()
    {
        return $this->belongsTo(ProductColorSize::class , 'product_color_size_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
