<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id')->withTrashed();
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id')->withTrashed();
    }

    public function questions()
    {
        return $this->morphMany(Question::class, 'model');
    }

    public function question_groups()
    {
        return $this->morphMany(QuestionGroup::class, 'model');
    }

    public function step()
    {
        return $this->hasOne(Step::class, 'quiz', 'id');
    }

    public function result()
    {
        return $this->hasOne(QuizResults::class);
    }

    public function chapterStudents()
    {
        return $this->morphMany(ChapterStudent::class, 'model');
    }

    public function isCompleted(){
        $isCompleted = $this->chapterStudents()->where('user_id', \Auth::id())->count();
        if($isCompleted > 0){
            return true;
        }
        return false;
    }
}
