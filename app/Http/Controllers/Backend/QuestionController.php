<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Question;
use App\Models\Test;
use App\Models\Quiz;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;

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

        dd($data);

        if(!isset($data['score'])) {
            $data['score'] = 1;
        }

        if(!isset($data['type'])) {
            $data['type'] = 0;
        }

        $question_data = [
            'question' => $data['question'],
            'model_id' => $data['model_id'],
            'score' => $data['score'],
            'type' => $data['type'],
            'user_id' => auth()->user()->id
        ];

        switch($data['model_type']) {
            case 'test':
                $question_data['model_type'] = Test::class;
            break;

            case 'quiz':
                $question_data['model_type'] = Quiz::class;
                $question_data['group_id'] = $data['group_id'];
            break;

            case 'assignment':
                $question_data['model_type'] = Assignment::class;
            break;

            default:
                $question_data['model_type'] = Quiz::class;
        }

        // Question image
        if(!empty($data['image'])) {
            $image = $request->file('image');
            $image_url = $this->saveImage($image, 'upload', true);
            $question_data['image'] = $image_url;
        }

        try {

            $question = Question::create($question_data);
            $question_count = Question::where('model_id', $data['model_id'])->where('model_type', $data['model_type'])->count();

            if($question_data['model_type'] == Test::class) {
                $html = $this->getTestHtml($question);
            }

            if($question_data['model_type'] == Quiz::class) {
                $html = $this->storeOptions($data, $question);
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

    /**
     * Add Question Section
     */
    public function addSection(Request $request)
    {
        $section = QuestionGroup::create([
            'title' => $request->section_title,
            'score' => $request->section_marks,
            'model_id' => $request->model_id,
            'model_type' => Quiz::class
        ]);

        $html = $this->getSectionHtml($section);

        return response()->json([
            'success' => true,
            'section' => $section,
            'html' => $html
        ]);
    }

    function storeOptions($data, $question)
    {
        $options = $data['option_text'];
        
        if($question->type == 0) {
            $answer_type = 'Single Answer';
        }

        switch($question->type) {
            case 0:
                $answer_type = 'Single Answer';
            break;

            case 1:
                $answer_type = 'Multi Answer';
            break;

            default:
                $answer_type = 'Single Answer';
        }

        $question_count = Question::where('model_type', Quiz::class)->where('group_id', $question->group_id)->count();

        $html = '<li class="list-group-item d-flex quiz-item">
                    <div class="flex d-flex flex-column">
                        <div class="card-title mb-16pt">' . $question_count . '. '. $question->question .'</div>
                        <div class="text-right">
                            <div class="chip chip-outline-secondary">'. $answer_type .'</div>
                            <div class="chip chip-outline-secondary">Score: '. $question->score .'</div>
                        </div>
                        <div class="options-wrap">
                        
                            <div class="form-group">
                                <div class="custom-controls-stacked">';
                                    foreach($options as $idx => $value) {
                                        $correct = 0;
                                        if($idx == (int)$data['option_single']) {
                                            $correct = 1;
                                        }
                                        $optionData = [
                                            'question_id' => $question->id,
                                            'option_text' => $value,
                                            'correct' => $correct
                                        ];
                                        $option = QuestionOption::create($optionData);
                                        $html .= $this->getOptionHtml($option);
                                    }
                                    $html .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </li>';

        return $html;
    }

    function getOptionHtml($option) {

        $option_count = QuestionOption::where('question_id', $option->question_id)->count();
        $checked_str = ($option->correct == 1) ? 'checked' : '';

        return '<div class="custom-control custom-radio mb-8pt">
                <input id="option_s'. $option->id .'_q'. $option->question_id .'" name="option_single_s'. $option->id .'_q'. $option->question_id .'" type="radio" class="custom-control-input" '. $checked_str .'>
                <label for="option_s'. $option->id .'_q'. $option->question_id .'" class="custom-control-label">'. $option->option_text .'</label>
            </div>';
    }

    function getSectionHtml($section)
    {
        $count = QuestionGroup::where('model_id', $section->model_id)->where('model_type', Quiz::class)->count();

        return '<div class="group-wrap py-32pt mb-16pt border-bottom-1" group-id="'. $section->id .'">
                    <div class="d-flex align-items-center page-num-container">
                        <div class="page-num">'. $count .'</div>
                        <div class="flex">
                            <div class="d-flex">
                                <h4 class="flex mb-0">'. $section->title .'</h4>
                                <h5 class="badge badge-pill font-size-16pt badge-accent">'. $section->score .'</h4>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary ml-16pt btn-question" data-id="'. $section->id .'">Add Quesion</button>
                    </div>
                </div>';
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
