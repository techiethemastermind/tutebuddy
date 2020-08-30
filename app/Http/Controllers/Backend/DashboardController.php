<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Course;
use App\Models\Schedule;
use App\User;
use App\Models\Test;
use App\Models\TestResults;
use DB;

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
                return view('backend.dashboard.super_admin');
            break;
            
            case 'admin':
                return view('backend.dashboard.admin');
            break;

            case 'teacher':
                return view('backend.dashboard.teacher');
            break;

            case 'student':

                // Get purchased Courses
                $courses_id = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
                $purchased_courses = Course::whereIn('id', $courses_id)->orderBy('created_at', 'desc')->limit(4)->get();

                $purchased_courses_count = Course::whereIn('id', $courses_id)->count();
                $total_courses_count = Course::all()->count();

                // Get instructors
                $teachers = User::role('Instructor')->limit(4)->get();
                $teachers_count = $teachers->count();

                // Get tests
                
                $testResults = TestResults::where('user_id', auth()->user()->id)->get();

                return view('backend.dashboard.student',
                    compact(
                        'purchased_courses',
                        'purchased_courses_count',
                        'total_courses_count',
                        'teachers',
                        'teachers_count',
                        'testResults'
                    )
                );

            break;

            default:
                return view('backend.dashboard.index');
        }
    }

    public function getStudentScheduleData()
    {
        $courses_id = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $schedules = Schedule::whereIn('course_id', $courses_id)->get();

        $data = [];
        $i = 0;

        foreach($schedules as $schedule) {
            $i++;
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';
            
            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded bg-primary text-white">'
                                            . substr($schedule->course->title, 0, 2) .
                                        '</span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-project">
                                                <strong>' . $schedule->course->title . '</strong></small>
                                            <small class="js-lists-values-location text-50">'. $schedule->course->teachers[0]->name .'</small>
                                        </div>
                                    </div>
                                </div>';
            $temp['lesson'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded bg-accent text-white">'
                                            . substr($schedule->lesson->title, 0, 2) .
                                        '</span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-project">
                                                <strong>' . $schedule->lesson->title . '</strong></small>
                                        </div>
                                    </div>
                                </div>';
            $temp['weekday'] = '<strong>' . Schedule::WEEK_DAYS[Carbon::parse($schedule->date)->dayOfWeek] . '</strong>';
            $temp['start'] = '<strong>' . $schedule->start_time . '</strong>';
            $temp['end'] = '<strong>' . $schedule->end_time . '</strong>';

            if($schedule->lesson->lesson_type == 1) {
                $temp['action'] = '<a href="' . route('lessons.live', [$schedule->lesson->slug, $schedule->lesson->id]) . '" target="_blank"
                        class="btn btn-primary btn-sm">Join</a>';
            } else {
                $temp['action'] = '<a href="' . route('lessons.show', [$schedule->course->slug, $schedule->lesson->slug, 1]) . '" target="_blank"
                        class="btn btn-primary btn-sm">Join</a>';
            }

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getStudentInstructorsData()
    {
        $teachers = User::role('Instructor')->get();
        
        $data = [];
        $i = 0;

        foreach($teachers as $user) {
            $i++;
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                                <label class="custom-control-label"><span class="text-hide">Check</span></label>
                            </div>';

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <img src="'. asset('/storage/avatars/' . $user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $user->about .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $btn_follow = '<a href="" target="_blank" class="btn btn-primary btn-sm">Follow</a>';
            $btn_profile = '<a href="" target="_blank" class="btn btn-accent btn-sm">View Profile</a>';
            $temp['action'] = $btn_follow . '&nbsp;' . $btn_profile;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

}
