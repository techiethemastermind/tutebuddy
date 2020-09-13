<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\TestResults;
use App\Models\Assignment;
use App\Models\Bundle;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        switch(auth()->user()->roles->pluck('slug')[0]) {
            case 'super_admin':

                $courses_count = Course::all()->count();
                $teachers_count = User::role('Instructor')->count();
                $students_count = User::role('Student')->count();

                return view('backend.dashboard.super_admin',
                    compact(
                        'courses_count',
                        'teachers_count',
                        'students_count'
                    )
                );
            break;
            
            case 'admin':
                return view('backend.dashboard.admin');
            break;

            case 'teacher':
                $courses = Course::all();
                $course_ids = $courses->pluck('id');
                $schedules = Schedule::whereIn('course_id', $course_ids)->orderBy('created_at', 'desc')->limit(5)->get();
                $student_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('user_id');
                $students = User::whereIn('id', $student_ids)->limit(5)->get();

                return view('backend.dashboard.teacher', compact('schedules', 'students'));
            break;

            case 'student':

                // Get purchased Course IDs
                $courses_id = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
                $lessons_id = Lesson::whereIn('course_id', $courses_id)->pluck('id');
                $teachers_id = DB::table('course_user')->whereIn('course_id', $courses_id)->pluck('user_id');
                $bundles_id = DB::table('bundle_student')->where('user_id', auth()->user()->id)->pluck('bundle_id');

                $purchased_courses = Course::whereIn('id', $courses_id)->orderBy('created_at', 'desc')->limit(5)->get();
                $schedules = Schedule::whereIn('course_id', $courses_id)->orderBy('created_at', 'desc')->limit(5)->get();
                $bundles = Bundle::whereIn('id', $bundles_id)->orderBy('created_at', 'desc')->limit(3)->get();
                $assignments = Assignment::whereIn('lesson_id', $lessons_id)->orderBy('created_at', 'desc')->limit(5)->get();
                $teachers = User::whereIn('id', $teachers_id)->limit(5)->get();
                $testResults = TestResults::where('user_id', auth()->user()->id)->limit(4)->get();

                return view('backend.dashboard.student',
                    compact(
                        'purchased_courses',
                        'schedules',
                        'bundles',
                        'assignments',
                        'teachers',
                        'testResults'
                    )
                );

            break;

            default:
                return view('backend.dashboard.index');
        }
    }
}
