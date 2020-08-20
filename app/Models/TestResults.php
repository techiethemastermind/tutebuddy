<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResults extends Model
{
    protected $guarded = [];
    
    public function answers()
    {
        return $this->hasMany(TestResultAnswers::class);
    }

    public function test(){
        return $this->belongsTo(Test::class);
    }
}
