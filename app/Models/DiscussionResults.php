<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscussionResults extends Model
{
    protected $fillable = ['discussion_id', 'user_id', 'content', 'post_user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
