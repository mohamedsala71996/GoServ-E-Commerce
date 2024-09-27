<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionReply extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'admin_id', 'reply'];


    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
