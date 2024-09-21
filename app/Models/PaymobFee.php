<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymobFee extends Model
{
    use HasFactory;

    protected $table = 'paymob_fees';

    protected $fillable = [
        'card_type',
        'percentage_fee',
        'fixed_fee',
    ];


}
