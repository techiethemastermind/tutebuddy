<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentResult extends Model
{
    protected $fillable = ['assignment_id', 'content', 'attachment_url', 'mark'];
}
