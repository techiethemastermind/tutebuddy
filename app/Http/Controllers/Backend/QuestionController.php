<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Question;
use App\Models\Test;
use App\Models\Quiz;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use FileUploadTrait;

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
     * List questions
     */
    public function index() {
        //
        $courses = Course::all();
        $first_course = $courses->first();
        $tests = $first_course->tests;

        return view('backend.question.index', compact('courses', 'tests'));
    }

    public function getList($course_id, $test_id) {

        $data = [];

        if($test_id != 0) {
            $test = Test::find($test_id);
            $questions = $test->questions;
            $data = $this->getArrayData($questions);

        } else {

            if($course_id != 0) {
                $course = Course::find($course_id);
                $tests = $course->tests;

                $all_questions = [];

                foreach($tests as $test) {
                    $questions = $test->questions;
                    if($questions->count() > 0) {
                        foreach($questions as $question) {
                            array_push($all_questions, $question);
                        }
                    }
                }

                $data = $this->getArrayData($all_questions);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Create a Question
     */
    public function store(Request $request) {

        $data = $request->all();

        if(!isset($data['score'])) {
            $data['score'] = 1;
        }

        if(!isset($data['type'])) {
            $data['type'] = 0;
        }

        switch($data['model_type']) {
            case 'test':
                $data['model_type'] = Test::class;
            break;

            case 'quiz':
                $data['model_type'] = Quiz::class;
            break;

            case 'assignment':
                $data['model_type'] = Assignment::class;
            break;

            default:
                $data['model_type'] = Quiz::class;
        }

        $question_data = [
            'question' => $data['question'],
            'model_id' => $data['model_id'],
            'score' => $data['score'],
            'type' => $data['type'],
            'model_type' => $data['model_type'],
            'user_id' => auth()->user()->id
        ];

        // Question image
        if(!empty($data['image'])) {
            $image = $request->file('image');
            $image_url = $this->saveImage($image, 'upload', true);
            $question_data['image'] = $image_url;
        }

        try {

            $question = Question::create($question_data);
            $question_count = Question::where('model_id', $data['model_id'])->where('model_type', $data['model_type'])->count();

            if($data['model_type'] == Test::class) {
                $html = $this->getTestHtml($question);
            }

            return response()->json([
                'success' => true,
                'question' => $question,
                'count' => $question_count,
                'html' => $html
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Edit Question
     */
    public function edit($id) {

        $courses = Course::all();
        $first_course = $courses->first();
        $tests = $first_course->tests;
        $question = Question::find($id);
        return view('backend.question.edit', compact('question', 'courses', 'tests'));
    }

    /**
     * Update a Question
     */
    public function update(Request $request, $id) {

        $update_data = [
            'question' => $request->question,
            'score' => $request->score,
            'model_id' => $request->model_id,
            'type' => $request->type
        ];

        if(!isset($request->score)) {
            $update_data['score'] = 1;
        }

        if(!isset($request->type)) {
            $update_data['type'] = 0;
        }

        switch($request->model_type) {
            case 'test':
                $update_data['model_type'] = Test::class;
            break;

            case 'quiz':
                $update_data['model_type'] = Quiz::class;
            break;

            case 'assignment':
                $update_data['model_type'] = Assignment::class;
            break;

            default:
            $update_data['model_type'] = Quiz::class;
        }
        
        // Question image
        if(!empty($request->image)) {
            $image = $request->file('image');

            // Delete existing img file
            if (File::exists(public_path('/storage/uploads/' . Question::find($id)->image))) {
                File::delete(public_path('/storage/uploads/' . Question::find($id)->image));
                File::delete(public_path('/storage/uploads/thumb/' . Question::find($id)->image));
            }

            $image_url = $this->saveImage($image, 'upload', true);
            $update_data['image'] = $image_url;
        }

        try {
            Question::find($id)->update($update_data);
            $question = Question::find($id);

            if($update_data['model_type'] == Test::class) {
                $html = $this->getTestHtml($question);
            }

            return response()->json([
                'success' => true,
                'question' => $question,
                'html' => $html
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete a Question
     */
    public function delete($id) {

        try {
            Question::find($id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Restore Question
     */
    public function restore($id) {

        //
    }

    function getTestHtml($question) {

        $test = Test::find($question->model_id);
        $count = $test->questions->count();
        $update_route = route('admin.questions.update', $question->id);
        $delete_route = route('admin.questions.delete', $question->id);

        $img = !empty($question->image) ? '<img class="img-fluid rounded" src="'. asset('/storage/uploads/' . $question->image) .'" alt="image">' : '';

        return '<li class="list-group-item d-flex quiz-item" data-id="'. $question->id .'">
                    <div class="flex d-flex flex-column">
                        <div class="card-title mb-16pt">Question ' . $count . '</div>
                        <div class="card-subtitle text-70 paragraph-max mb-8pt tute-question">'. $question->question .'</div>
                        '. $img .'
                        <input type="hidden" name="score" value="'. $question->score .'">
                    </div>

                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                class="material-icons">more_horiz</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="'. $update_route .'" class="dropdown-item edit">Edit Question</a>
                            <div class="dropdown-divider"></div>
                            <a href="'. $delete_route .'" class="dropdown-item text-danger delete">Delete Question</a>
                        </div>
                    </div>
                </li>';
    }

    function getQuizHtml($question) {

        $quiz = Quiz::find($question->model_id);
        $count = $quiz->questions->count();
        $answer_str = ($question->type == 1) ? 'Single Answer' : 'Multi Answer';
        $edit_route = route('admin.questions.edit', $question->id);
        $delete_route = route('admin.questions.delete', $question->id);

        return '<li class="list-group-item d-flex quiz-item">
                    <div class="flex d-flex flex-column">
                        <div class="card-title mb-16pt">Question ' . $count . '</div>
                        <div class="card-subtitle text-70 paragraph-max mb-16pt" id="content_quiz_'. $question->id . '"></div>

                        <div class="work-area d-none">
                            <div id="editor_quiz_'. $question->id . '"></div>
                            <textarea id="quiz_'. $question->id . '" class="quiz-textarea">'. $question->question .'</textarea>
                        </div>
                        
                        <div class="text-right">
                            <div class="chip chip-outline-secondary">'. $answer_str .'</div>
                            <div class="chip chip-outline-secondary">Score: '. $question->score .'</div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                class="material-icons">more_horiz</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="'. $edit_route .'" class="dropdown-item">Edit Question</a>
                            <div class="dropdown-divider"></div>
                            <a href="'. $delete_route .'" class="dropdown-item text-danger">Delete Question</a>
                        </div>
                    </div>
                </li>';
    }

    function getArrayData($questions) {

        $data = [];
        $i = 0;

        foreach($questions as $question) {

            $inserts = '';
            // Get question title
            foreach(json_decode($question->question) as $item) {
                $inserts .= $item->insert;
            }

            $title = (strlen($inserts) > 50) ? substr($inserts, 0, 50) . '...' : $inserts;

            $i++;
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';
            $temp['no'] = $i;
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . substr($title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <p class="mb-0"><strong>' . $title . '</strong></p>
                                        <small class="text-50">Test Question</small>
                                    </div>
                                </div>
                            </div>';

            $temp['options'] = $question->options->count();

            $show_route = route('admin.questions.show', $question->id);
            $edit_route = route('admin.questions.edit', $question->id);
            $delete_route = route('admin.questions.destroy', $question->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($question->trashed()) {
                $restore_route = route('admin.questions.restore', $question->id);
                $btn_delete = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Recover"><i class="material-icons">restore_from_trash</i></a>';
            }

            $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
            $temp['more'] = '<a href="javascript:void(0)" class="text-50"><i class="material-icons">more_vert</i></a>';

            array_push($data, $temp);
        }

        return $data;
    }
}
