<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\User;
use App\Models\Lesson;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use SoftDeletes;

    public $table = 'course';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (auth()->user()->hasRole('Instructor') && request()->is('dashboard/*')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->whereHas('teachers', function ($q) {
                        $q->where('course_user.user_id', '=', auth()->user()->id);
                    });
                });
            }

            if (auth()->user()->hasRole('Student')) {
                static::addGlobalScope('filter', function (Builder $builder) {
                    $builder->where('published', 1);
                });
            }
        }

        if(request()->route()->uri() == '/') {
            static::addGlobalScope('filter', function (Builder $builder) {
                $builder->where('published', 1);
            });
        }

        static::deleting(function ($course) { // before delete() method call this
            if ($course->isForceDeleting()) {
                if (File::exists(public_path('/storage/uploads/' . $course->course_image))) {
                    File::delete(public_path('/storage/uploads/' . $course->course_image));
                    File::delete(public_path('/storage/uploads/thumb/' . $course->course_image));
                }
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_user')->withPivot('user_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student')->withTimestamps()->withPivot(['rating']);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function publishedLessons()
    {
        return $this->hasMany(Lesson::class)->where('published', 1);
    }

    public function tests()
    {
        return $this->hasMany(Quiz::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function mediaVideo()
    {
        $types = ['video', 'pdf', 'audio', 'embed'];
        return $this->morphOne(Media::class, 'model')
            ->whereIn('type', $types);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function duration()
    {
        $d = 0;

        foreach($this->lessons as $lesson) {
            $d += $lesson->lessonDuration();
        }

        $hours = floor($d / 60);
        $minutes = ($d % 60);

        return $hours . 'h ' . $minutes . 'm';
    }

    public function progress()
    {
        if(auth()->check()) {
            $completed_lessons = \Auth::user()->chapters()
                ->where('model_type', Lesson::class)
                ->where('course_id', $this->id)
                ->pluck('model_id')->toArray();
            
            if (count($completed_lessons) > 0) {
                return intval(count($completed_lessons) / $this->lessons->count() * 100);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function isUserCertified()
    {
        $status = false;
        $certified = auth()->user()->certificates()->where('course_id', '=', $this->id)->first();
        if ($certified != null) {
            $status = true;
        }
        return $status;
    }

    public function isEnrolled()
    {
        return (auth()->check() && $this->students()->where('user_id', '=', auth()->user()->id)->count() > 0);
    }

    public function isReviewed()
    {
        return (auth()->check() && $this->reviews()->where('user_id', '=', auth()->user()->id)->count() > 0);
    }

    public function favorited()
    {
        $favorite = DB::table('course_favorite')->where('course_id', $this->id)->where('user_id', auth()->user()->id)->first();
        return ($favorite) ? true : false;
    }
}
