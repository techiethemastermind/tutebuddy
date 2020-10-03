<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Lesson;

class PaperTest extends Model
{
    use SoftDeletes;

    protected $fillable = ['lesson_id', 'title', 'content', 'type', 'due_date', 'attachment', 'due_date'];

    protected static function boot()
    {
        parent::boot();
        
        if (auth()->check()) {
            if (auth()->user()->hasRole('Instructor')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('user_id', '=', Auth::user()->id);
                });
            }
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function result()
    {
        return $this->hasOne(PaperTestResult::class);
    }
}
