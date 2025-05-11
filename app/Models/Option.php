<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = ['content', 'is_correct', 'question_id'];
    public function question() { return $this->belongsTo(Question::class); }
    public function studentAnswers()
{
    return $this->hasMany(StudentAnswer::class);
}
}
