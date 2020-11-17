<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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
use App\Models\Quiz;
use App\Models\QuizResults;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;

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
                $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
                $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');
                $live_lesson_ids = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->pluck('id');
                $schedules = Schedule::whereIn('lesson_id', $live_lesson_ids)->orderBy('updated_at', 'desc')->limit(5)->get();

                $course_students = DB::table('course_student')->whereIn('course_id', $course_ids)->get();
                $students = collect();
                foreach($course_students as $item) {
                    $c_item = Course::find($item->course_id);
                    $u_item = User::find($item->user_id);
                    $data = [
                        'course' => $c_item,
                        'user' => $u_item
                    ];
                    $students->push($data);
                }

                $assignments = Assignment::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->limit(5)->get();
                $assignment_ids = Assignment::where('user_id', auth()->user()->id)->pluck('id');
                $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids)->limit(5)->get();
                $bundles = Bundle::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->limit(3)->get();
                $test_ids = Test::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
                $testResults = TestResult::whereIn('test_id', $test_ids)->limit(5)->get();
                $quiz_ids = Quiz::whereIn('course_id', $course_ids)->limit(5)->pluck('id');
                $quizResults = QuizResults::whereIn('quiz_id', $quiz_ids)->limit(5)->get();
                $discussions = Discussion::limit(5)->get();

                $earned_this_month = $this->getEarned('month');
                $balance = $this->getEarned('balance');
                $total = $this->getEarned('total');

                $pending_orders = collect();
                $order_items = OrderItem::where('item_id', $course_ids)->orderBy('created_at', 'desc')->get();
                foreach($order_items as $item) {
                    if(Carbon::parse($item->course->end_date)->diffInDays(Carbon::now()) < 7 ||
                            $item->course->end_date > Carbon::now()->format('Y-m-d')) {
                                $pending_orders->push($item);
                    }
                }

                return view('backend.dashboard.teacher', 
                    compact(
                        'pending_orders',
                        'courses',
                        'schedules',
                        'students',
                        'assignments', 
                        'assignment_results',
                        'bundles',
                        'testResults',
                        'quizResults',
                        'discussions',
                        'earned_this_month',
                        'balance',
                        'total'
                    )
                );
            break;

            case 'student':

                // Get purchased Course IDs
                $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
                $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');
                $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
                $teachers_id = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');
                $bundle_ids = DB::table('bundle_student')->where('user_id', auth()->user()->id)->pluck('bundle_id');

                $purchased_courses = Course::whereIn('id', $course_ids)->get();
                $live_lesson_ids = Lesson::whereIn('course_id', $course_ids)->where('lesson_type', 1)->pluck('id');
                $schedules = Schedule::whereIn('lesson_id', $live_lesson_ids)->orderBy('updated_at', 'desc')->limit(5)->get();

                $bundles = Bundle::whereIn('id', $bundle_ids)->limit(3)->get();
                $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->limit(5)->get();
                $teachers = User::whereIn('id', $teachers_id)->limit(5)->get();
                $testResults = TestResult::where('user_id', auth()->user()->id)->limit(4)->get();
                $discussions = Discussion::limit(5)->get();

                // Parent Categories
                $parentCategories = Category::where('parent', 0)->get();

                return view('backend.dashboard.student',
                    compact(
                        'purchased_courses',
                        'schedules',
                        'bundles',
                        'assignments',
                        'teachers',
                        'testResults',
                        'discussions',
                        'parentCategories'
                    )
                );

            break;

            default:
                return view('backend.dashboard.index');
        }
    }

    /**
     * Get earned
     */
    private function getEarned($type)
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $purchased_ids = DB::table('course_student')->whereIn('course_id', $course_ids)->pluck('course_id');
        $earned = 0;

        switch($type) {
            case 'month':

                $start = new Carbon('first day of this month');
                $now = Carbon::now();
                $end = new Carbon('last day of this month');

                // Get courses end_date is in this month
                $course_ids_this_month = Course::whereBetween('end_date', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $earned = OrderItem::whereIn('item_id', $course_ids_this_month)
                        ->whereBetween('created_at', [$start->format('Y-m-d')." 00:00:00", $now->format('Y-m-d')." 23:59:59"])
                        ->sum('price');

                return $earned;
            break;

            case 'balance':
                $now = Carbon::now();
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                $withdraws = Transaction::where('user_id', auth()->user()->id)->where('type', 'withdraw')->sum('amount');
                $balance = $total - $withdraws;
                return $balance;
            break;

            case 'total':
                $now = Carbon::now();
                // Get courses end_date to today
                $course_ids_since_now = Course::where('end_date', '<', $now->format('Y-m-d')." 23:59:59")
                    ->whereIn('id', $purchased_ids)
                    ->pluck('id');

                $total = OrderItem::whereIn('item_id', $course_ids_since_now)->sum('price');
                return $total;
            break;
        }

        
    }
}
