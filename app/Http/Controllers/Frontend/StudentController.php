<?php

/**
 * This controller is for All of actions for Frontend
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizResults;
use App\Models\QuizResultAnswers;
use App\Models\ChapterStudent;

class StudentController extends Controller
{
    public function startQuiz($lesson_slug, $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        return view('frontend.course.quiz', compact('quiz'));
    }

    /**
     * Save Quiz
     * 
     */
    public function saveQuiz(Request $request)
    {
        $data = $request->all();

        $quiz_result = QuizResults::create([
            'quiz_id' => $data['quiz_id'],
            'user_id' => auth()->user()->id,
        ]);

        foreach($data as $key => $value) {
            
            if (strpos($key, 'option') !== false) {
                
                if(strpos($key, 'option_single_q') !== false) {
                    $question_id = (int)substr($key, strlen('option_single_q'));
                    $option_id = (int)$value;

                    $this->completeQuestion($quiz_result->id, $question_id, $option_id);
                }

                if(strpos($key, 'option_multi_q') !== false) {
                    $question_id = (int)substr($key, strlen('option_multi_q'));
                    $option_ids = $value;
                    foreach($option_ids as $option_id) {
                        $this->completeQuestion($quiz_result->id, $question_id, $option_id);
                    }
                }

                if(strpos($key, 'option_blank_q') !== false) {
                    $question_id = (int)substr($key, strlen('option_multi_q'), strpos($key, '__option'));
                    $option_id = (int)substr($key, strpos($key, '__option') + 8);
                    $this->completeQuestion($quiz_result->id, $question_id, $option_id, $value);
                }
            }
        }

        $quiz = Quiz::find($data['quiz_id']);
        $update_data = [
            'model_type' => Quiz::class,
            'model_id' => $data['quiz_id'],
            'user_id' => auth()->user()->id,
            'course_id' => $quiz->course->id,
            'lesson_id' => $quiz->lesson->id
        ];

        try {
            ChapterStudent::updateOrCreate($update_data, $update_data);
            $this->setQuizScore($quiz);
            return response()->json([
                'success' => true,
                'action' => 'complete'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    function setQuizScore($quiz)
    {
        // Quiz Score
        $corrects = [];
        $total_score = 0;
        $quiz_score = 0;
        $questions = $quiz->questions;

        foreach($questions as $question) {
            if(!empty($question->score)) {
                $total_score += $question->score;
            }
            $corrects = $question->options->where('correct', 1)->pluck('id')->toArray();
            $answers = $quiz->result->answers->where('question_id', $question->id)->pluck('option_id')->toArray();
            $result = array_diff($answers, $corrects);

            if(empty($result) && !empty($question->score)) {
                $quiz_score += $question->score;
            }
        }

        $score = floor(( $quiz_score / $total_score ) * 100);
        
        $quiz->result->quiz_result = $score;
        $quiz->result->save();
    }

    /**
     * Complete a Question
     */
    function completeQuestion($quiz_results_id, $question_id, $option_id, $answer = null)
    {
        // Find existing results and add to TestResult
        $answer_data = [
            'quiz_results_id' => $quiz_results_id,
            'question_id' => $question_id,
            'option_id' => $option_id
        ];

        if(!empty($answer)) {
            $answer_data['answer'] = $answer;
        }

        $answer = QuizResultAnswers::create($answer_data);

        return true;
    }

    /**
     * Show Quiz Result
     */
    public function quizResult($lesson_slug, $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        return view('frontend.course.quiz_result', compact('quiz'));
    }
}
