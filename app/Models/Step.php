<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public $table = 'lesson_steps';
    protected $guarded = [];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function getTest()
    {
        return $this->hasOne(Test::class, 'id', 'test');
    }
}
