<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;

class CoursesController extends Controller
{
    //
    public function index()
    {
        // List of Courses
    }

    /**
     * Show Selected Course
     */
    public function show ($slug)
    {
        $course = Course::where('slug', $slug)->first();

        $course_rating = 0;
        $total_ratings = 0;
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }

        return view('frontend.course.course', compact('course', 'course_rating', 'total_ratings'));
    }
}
