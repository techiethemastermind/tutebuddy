<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'slug', 'logo', 'content', 'editor'];
}
