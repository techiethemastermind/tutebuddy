<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;

class LessonsController extends Controller
{
    /**
     * Show selected lesson
     */
    public function show ($course_slug, $slug, $order)
    {
        $course = Course::where('slug', $course_slug)->first();
        $lesson = Lesson::where('course_id', $course->id)->where('slug', $slug)->first();
        $step = Step::where('lesson_id', $lesson->id)->where('step', $order)->first();
        $next = Step::where('lesson_id', $lesson->id)->where('step', $order + 1)->first();
        return view('frontend.course.lesson', compact('lesson', 'step', 'next'));
    }
}
