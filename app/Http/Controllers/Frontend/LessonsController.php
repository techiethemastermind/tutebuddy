<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\DB;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestResults;
use App\Models\TestResultAnswers;
use App\Models\ChapterStudent;
use App\Models\Assignment;
use App\Models\AssignmentResult;

use Carbon\Carbon;

class LessonsController extends Controller
{
    use FileUploadTrait;

    /**
     * Show selected lesson
     */
    public function show($course_slug, $slug, $order)
    {
        $course = Course::where('slug', $course_slug)->first();
        $lesson = Lesson::where('course_id', $course->id)->where('slug', $slug)->first();
        $prev = Step::where('lesson_id', $lesson->id)->where('step', $order - 1)->first();
        $step = Step::where('lesson_id', $lesson->id)->where('step', $order)->first();
        $next = Step::where('lesson_id', $lesson->id)->where('step', $order + 1)->first();
        $discussions = $course->discussions->take(5);

        if($step->type == 'test') {

            $test = $step->getTest;
            $completed = $test->isCompleted();

            if($completed) {
                $test_result = TestResults::where('test_id', $test->id)->where('user_id', auth()->user()->id)->first();
                $test_answers = TestResultAnswers::where('test_result_id', $test_result->id)->get();
                $questions = $test->questions;
                return view('frontend.course.test_result', compact('lesson', 'test', 'test_result', 'step', 'test_answers', 'questions', 'next'));
            } else {
                $testStep = 0;
                return view('frontend.course.test', compact('lesson', 'step', 'test', 'testStep'));
            }
            
        } else {
            return view('frontend.course.lesson', compact('lesson', 'step', 'prev', 'next', 'discussions'));
        }
    }

    /**
     * Get progress
     */
    public function courseProgress(Request $request)
    {
        if (\Auth::check()) {
            $lesson = Lesson::find($request->model_id);
            if ($lesson != null) {
                if ($lesson->chapterStudents()->where('user_id', \Auth::id())->get()->count() == 0) {
                    $lesson->chapterStudents()->create([
                        'model_type' => $request->model_type,
                        'model_id' => $request->model_id,
                        'user_id' => auth()->user()->id,
                        'course_id' => $lesson->course->id
                    ]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Live Lesson
     */
    public function liveSession($slug, $id)
    {
        $lesson = Lesson::find($id);
        $course = $lesson->course;
        $schedule = $lesson->schedule;

        $attendeePW = 'ap';
        $moderatorPW = 'mp';
        $meeting_name = preg_replace('/\s+/', '+', $course->title . ' - ' . $lesson->title . ' ' . $schedule->start_time . ' to ' . $schedule->end_time);
        $duration = strtotime($schedule->end_time) - strtotime($schedule->start_time);
        
        if(auth()->user()->hasRole('Instructor') || auth()->user()->hasRole('Administrator')) {

            $meeting_id = 'live-' . substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 9);
            $room_str = 'name=' . $meeting_name . '&meetingID=' . $meeting_id . '&attendeePW=' . $attendeePW . '&moderatorPW=' . $moderatorPW;
            $room_str .= '&createdTime=' . $schedule->start_time . '&duration=' . $duration;
            
            $create_room_str = 'create' . $room_str . config('liveapp.key');
            $checksum = sha1($create_room_str);
            $room_str_checksum = $room_str . '&checksum=' . $checksum;

            $endpoint = config('liveapp.url') . 'bigbluebutton/api/create?' . $room_str_checksum;

            // Create room
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($ch);
            curl_close($ch);

            $json = json_encode(simplexml_load_string($output));
            $array = json_decode($json, true);

            if($array['returncode'] == 'SUCCESS') {
                $meetingId = $array['meetingID'];
                $lesson->meeting_id = $meetingId;
                $lesson->save();
            }

            // Load with Manager
            $url = config('liveapp.url') . 'bigbluebutton/api/join?';
            $room_str = 'fullName=' . preg_replace('/\s+/', '+', auth()->user()->name) 
                            . '&meetingID=' . $lesson->meeting_id . '&password=' . $moderatorPW;

            $join_room_str = 'join' . $room_str . config('liveapp.key');
            
            $checksum = sha1($join_room_str);
            $join_room = json_encode($url . $room_str . '&checksum=' . $checksum);

            return view('frontend.live', compact('join_room'));
        }

        if(auth()->user()->hasRole('Student')) {

            // Set lesson completed
            $update_data = [
                'model_type' => Lesson::class,
                'model_id' => $lesson->id,
                'user_id' => auth()->user()->id,
                'course_id' => $lesson->course->id
            ];

            ChapterStudent::updateOrCreate($update_data, $update_data);

            $url = config('liveapp.url') . 'bigbluebutton/api/join?';
            $room_str = 'fullName=' . preg_replace('/\s+/', '+', auth()->user()->name) 
                            . '&meetingID=' . $lesson->meeting_id . '&password=' . $attendeePW;

            $join_room_str = 'join' . $room_str . config('liveapp.key');
            
            $checksum = sha1($join_room_str);
            $join_room = $url . $room_str . '&checksum=' . $checksum;

            return view('frontend.live', compact('join_room'));
        }
    }
}
