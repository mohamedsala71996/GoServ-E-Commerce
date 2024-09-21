<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnAndReplacement extends Model
{
    use HasFactory;

    protected $table='returns_and_replacements';
    protected $fillable = [
        'order_id',
        'user_id',
        'reason',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
