<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = ['test_id', 'user_id', 'content', 'attachment', 'mark', 'status'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
