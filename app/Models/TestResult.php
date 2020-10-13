<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = ['tests_id', 'content', 'attachment_url', 'mark'];

    public function assignment()
    {
        return $this->belongsTo(Test::class);
    }
}
