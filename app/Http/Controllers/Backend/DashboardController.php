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
use App\Models\TestResult;
use App\Models\Assignment;
use App\Models\Bundle;
use App\Models\AssignmentResult;
use App\Models\TestResultAnswers;
use App\Models\Discussion;

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
                // $course_ids = $courses->pluck('id');
                $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->limit(5)->pluck('course_id');
                $live_lessons = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->limit(5)->get();
                $schedules = Schedule::whereIn('course_id', $course_ids)->whereNotNull('lesson_id')->orderBy('created_at', 'desc')->limit(5)->get();
                $student_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('user_id');
                $students = User::whereIn('id', $student_ids)->limit(5)->get();
                $assignments = Assignment::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->limit(5)->get();
                $assignment_ids = Assignment::where('user_id', auth()->user()->id)->pluck('id');
                $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids)->limit(5)->get();
                $bundles = Bundle::where('user_id', auth()->user()->id)->limit(5)->get();
                $test_ids = Test::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
                $testResults = TestResult::whereIn('test_id', $test_ids)->limit(5)->get();
                $discussions = Discussion::limit(5)->get();

                return view('backend.dashboard.teacher', compact('schedules', 'live_lessons',
                    'students', 'assignments', 'assignment_results', 'bundles', 'testResults', 'discussions'));
            break;

            case 'student':

                // Get purchased Course IDs
                $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
                $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
                $teachers_id = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');
                $bundle_ids = DB::table('bundle_student')->where('user_id', auth()->user()->id)->pluck('bundle_id');

                $purchased_courses = Course::whereIn('id', $course_ids)->limit(5)->get();
                $schedules = Schedule::whereIn('course_id', $course_ids)->limit(5)->get();
                $live_lessons = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->limit(5)->get();
                $bundles = Bundle::whereIn('id', $bundle_ids)->limit(3)->get();
                $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->limit(5)->get();
                $teachers = User::whereIn('id', $teachers_id)->limit(5)->get();
                $testResults = TestResult::where('user_id', auth()->user()->id)->limit(4)->get();
                $discussions = Discussion::limit(5)->get();
                return view('backend.dashboard.student',
                    compact(
                        'purchased_courses',
                        'schedules',
                        'live_lessons',
                        'bundles',
                        'assignments',
                        'teachers',
                        'testResults',
                        'discussions'
                    )
                );

            break;

            default:
                return view('backend.dashboard.index');
        }
    }
}
