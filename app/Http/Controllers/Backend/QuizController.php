<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Quiz;

class QuizController extends Controller
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
     * List of Quiz
     */
    public function index() {

        $courses = Course::all();
        return view('backend.quiz.index', compact('courses'));
    }

    /**
     * Get Quizs by Course id
     */
    public function getList($id) {

        $quizs = quiz::where('course_id', $id)->get();
        $data = $this->getArrayData($quizs);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Add new quiz
     */
    public function create() {

        $courses = Course::all();
        return view('backend.quiz.create', compact('courses'));
    }

    /**
     * Store a Question
     */
    public function store(Request $request) {

        $data = $request->all();
        $quiz_data = [
            'course_id' => $data['course_id'],
            'title' => $data['title'],
            'description' => $data['quiz_description']
        ];
        
        try {
            $quiz = quiz::create($quiz_data);

            return response()->json([
                'success' => true,
                'quiz' => $quiz
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
        
    }

    /**
     * Edit a quiz
     */
    public function edit($id) {

        $courses = Course::all();
        $quiz = quiz::find($id);
        return view('backend.quiz.edit', compact('quiz', 'courses'));
    }

    /**
     * Update a quiz
     */
    public function update(Request $request, $id) {

        $updateData = [
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->short_description 
        ];

        try {
            quiz::find($id)->update($updateData);

            return response()->json([
                'success' => true,
                'action' => 'update'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete quiz
     */
    public function destroy($id) {

        try {
            quiz::find($id)->delete();

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

    function getArrayData($quizs) {
        $data = [];
        $i = 0;

        foreach($quizs as $quiz) {
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
                                        . substr($quiz->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $quiz->title . '</strong></small>
                                    </div>
                                </div>
                            </div>';

            $temp['questions'] = $quiz->questions->count();

            if(!empty($quiz->lesson_id)) {
                $temp['assigned'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">' . $quiz->lesson->name . '</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['assigned'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">No Assigned</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }

            $show_route = route('admin.quizs.show', $quiz->id);
            $edit_route = route('admin.quizs.edit', $quiz->id);
            $delete_route = route('admin.quizs.destroy', $quiz->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($quiz->trashed()) {
                $restore_route = route('admin.quizs.restore', $quiz->id);
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
