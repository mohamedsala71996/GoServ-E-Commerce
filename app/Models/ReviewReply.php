<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    use HasFactory;

    protected $fillable = ['review_id', 'admin_id', 'reply'];


    public function review()
    {
        return $this->belongsTo(ProductReview::class, 'review_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
