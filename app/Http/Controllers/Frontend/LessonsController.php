<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestResults;
use App\Models\TestResultAnswers;

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

        if($step->type == 'test') {
            $test = $step->getTest;
            $testStep = 0;
            return view('frontend.course.test', compact('lesson', 'step', 'test', 'testStep'));
        } else {
            return view('frontend.course.lesson', compact('lesson', 'step', 'next'));
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
        return view('frontend.course.test_result', compact('lesson', 'test', 'test_result', 'step', 'test_answers', 'questions'));
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
}
