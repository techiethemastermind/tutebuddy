<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    // one to many
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent', 'id');
    }

    public function level()
    {
        return $this->hasOne(Level::class);
    }
}
