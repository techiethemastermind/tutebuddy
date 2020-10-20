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
     * get a Question
     */
    public function getQuestion($id, $index)
    {
        $test = Test::find($id);
        $question = $test->questions->skip($index)->take(1)->first();

        if(!empty($question)) {
            $options = $question->options;

            $options_html = $this->getOptionHtml($options);
            
            return response()->json([
                'success' => true,
                'data' => $question,
                'html' => $options_html
            ]);
        } else {

            return response()->json([
                'success' => false,
                'msg' => 'End of Test'
            ]);
        }
    }

    /**
     * Result of Test
     */
    public function testResult($id)
    {
        $test = Test::find($id);
        $lesson = $test->lesson;
        $test_result = TestResults::where('test_id', $test->id)->where('user_id', auth()->user()->id)->first();
        $test_answers = TestResultAnswers::where('test_result_id', $test_result->id)->get();
        $questions = $test->questions;
        $step = $test->step;
        $next = Step::where('lesson_id', $lesson->id)->where('step', $step->step + 1)->first();
        return view('frontend.course.test_result', compact('lesson', 'test', 'test_result', 'step', 'test_answers', 'questions', 'next'));
    }

    /**
     * Complete a Question
     */
    function completeQuestion(Request $request, $id)
    {
        $question = Question::find($id);
        $test = $question->tests;

        // Get option to compare
        $answers = $request->answers;

        if(empty($answers)) {
            return response()->json([
                'success' => false,
                'msg' => 'Not selected Answer'
            ]);
        }

        $corrects = [];
        $options = $question->options->where('correct', 1)->all();
        foreach($options as $option) {
            array_push($corrects, $option->id);
        }

        $result = array_diff($answers, $corrects);
    
        $test_result_val = 0;
        if(empty($result)) {
            $test_result_val = 1;
        }

        // Find existing results and add to TestResult
        $test_result = TestResults::where('test_id', $test->id)->where('user_id', auth()->user()->id)->first();
        if(!empty($test_result)) {

            $test_result->test_result = $test_result->test_result + $test_result_val;
            $test_result->save();
        } else {
            $test_result_data = [
                'test_id' => $test->id,
                'user_id' => auth()->user()->id,
                'test_result' => $test_result_val
            ];

            $test_result = TestResults::create($test_result_data);
        }

        // Add Test result Answer
        foreach($answers as $answer) {
            $query = [
                'test_result_id' => $test_result->id,
                'question_id' => $question->id,
                'option_id' => $answer,
            ];

            $answer_data = [
                'test_result_id' => $test_result->id,
                'question_id' => $question->id,
                'option_id' => $answer,
                'correct' => 1
            ];

            TestResultAnswers::updateOrCreate($query, $answer_data);
        }

        return response()->json([
            'success' => true
        ]);
        
    }

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

            return Redirect::to($join_room);
        }
    }

    function getOptionHtml($options)
    {
        $html = '';
        foreach($options as $option) {
            $html .= '<div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input id="opt__'. $option->id .'" type="checkbox" class="custom-control-input">
                            <label for="opt__'. $option->id .'" class="custom-control-label">' . $option->option_text . '</label>
                        </div>
                    </div>';
        }

        return $html;
    }

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

    public function completeStep($id, $type)
    {
        $step = Step::find($id);
        $course = $step->lesson->course;
        $update_data = [
            'model_type' => Step::class,
            'model_id' => $id,
            'user_id' => auth()->user()->id,
            'course_id' => $course->id
        ];

        if($type == 1) {
            try {
                ChapterStudent::updateOrCreate($update_data, $update_data);
    
                return response()->json([
                    'success' => 'true',
                    'action' => 'complete'
                ]);
            } catch (Exception $e) {
    
                return response()->json([
                    'success' => 'false',
                    'msg' => $e->getMessage()
                ]);
            }
        } elseif ($type == 0) {

            try {
                ChapterStudent::where('model_type', $update_data['model_type'])
                    ->where('model_id', $update_data['model_id'])
                    ->where('user_id', $update_data['user_id'])
                    ->delete();
    
                return response()->json([
                    'success' => 'true',
                    'action' => 'uncomplete'
                ]);
            } catch (Exception $e) {
    
                return response()->json([
                    'success' => 'false',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }

    public function completeLesson($id)
    {
        $lesson = Lesson::find($id);
        $update_data = [
            'model_type' => Lesson::class,
            'model_id' => $id,
            'user_id' => auth()->user()->id,
            'course_id' => $lesson->course->id
        ];

        try {
            ChapterStudent::updateOrCreate($update_data, $update_data);
            return redirect()->route('courses.show', $lesson->course->slug);
        } catch (Exception $e) {

            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function testComplete($id)
    {
        $test = Test::find($id);
        $update_data = [
            'model_type' => Test::class,
            'model_id' => $id,
            'user_id' => auth()->user()->id,
            'course_id' => $test->course->id
        ];

        try {
            ChapterStudent::updateOrCreate($update_data, $update_data);
            return redirect()->route('courses.show', $test->course->slug);
        } catch (Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function assignment($id)
    {
        $assignment = Assignment::find($id);
        return view('frontend.course.assignment', compact('assignment'));
    }

    public function saveAssignment(Request $request)
    {
        $data = $request->all();

        // Find Assignment Result
        $assignment = Assignment::find($request->assignment_id);
        $result = $assignment->result;
        $data['submit_date'] = Carbon::now()->format('Y-m-d H:i:s');

        if(!$result) {
            // Attachment URL
            if(!empty($request->doc_file)) {
                $file = $request->file('doc_file');
                $file_url = $this->saveFile($file);
                $data['attachment_url'] = $file_url;
            }
            $data['user_id'] = auth()->user()->id;
            
            AssignmentResult::create($data);
        } else {

            // Attachment URL
            if(!empty($request->doc_file)) {
                $file = $request->file('doc_file');

                // Delete existing img file
                if (File::exists(public_path('/storage/uploads/' . $assignment->attachment_url))) {
                    File::delete(public_path('/storage/uploads/' . $assignment->attachment_url));
                    File::delete(public_path('/storage/uploads/thumb/' . $assignment->attachment_url));
                }

                $file_url = $this->saveFile($file);
                $result->attachment_url = $file_url;
            }

            $result->content = $request->content;
            $result->submit_date = $data['submit_date'];
            $result->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved',
        ]);
    }
}
