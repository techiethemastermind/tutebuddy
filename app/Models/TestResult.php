<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TestResult extends Model
{
    protected $fillable = ['test_id', 'user_id', 'content', 'attachment', 'mark', 'status'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
