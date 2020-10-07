<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AssignmentResult extends Model
{
    protected $fillable = ['assignment_id', 'user_id', 'content', 'attachment_url', 'mark'];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
