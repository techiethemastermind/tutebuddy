<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResults extends Model
{
    protected $guarded = [];
    
    public function answers()
    {
        return $this->hasMany(QuizResultAnswers::class);
    }

    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }
}
