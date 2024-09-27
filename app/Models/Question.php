<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;


    protected $fillable = ['user_id', 'question', 'status', 'topic'];

    // Define relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(QuestionReply::class, 'question_id');
    }
}
