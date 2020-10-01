<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperTestResult extends Model
{
    protected $fillable = ['paper_test_id', 'content', 'attachment_url', 'mark'];

    public function assignment()
    {
        return $this->belongsTo(PaperTest::class);
    }
}
