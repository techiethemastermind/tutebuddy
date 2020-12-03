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

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function parent()
    {
        return $this->belongsTo(DiscussionResults::class, 'post_user_id', 'user_id');
    }

    public function childs()
    {
        return $this->hasMany(DiscussionResults::class, 'post_user_id', 'user_id');
    }
}
