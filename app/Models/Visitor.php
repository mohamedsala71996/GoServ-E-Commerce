<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'visitors';

    // Define the fillable fields to allow mass assignment
    protected $fillable = [
        'ip_address',
        'visit_count',
    ];
}
