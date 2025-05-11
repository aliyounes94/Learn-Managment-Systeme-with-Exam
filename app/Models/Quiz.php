<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'class_id', 'subject_id', 'duration_minutes'];
    public function questions() { return $this->hasMany(Question::class); }
    public function myClass()
{
    return $this->belongsTo(MyClass::class, 'class_id'); // Make sure this matches your foreign key
}

public function subject()
{
    return $this->belongsTo(Subject::class, 'subject_id');
}

}
