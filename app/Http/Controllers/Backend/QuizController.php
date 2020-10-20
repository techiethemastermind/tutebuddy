<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuestionGroup;

use Illuminate\Support\Facades\DB;

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
        $count = [
            'all' => Quiz::all()->count(),
            'published' => Quiz::where('published', 1)->count(),
            'pending' => Quiz::where('published', 0)->count(),
            'deleted' => Quiz::onlyTrashed()->count()
        ];

        return view('backend.quiz.index', compact('count'));
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        switch ($type) {
            case 'all':
                $quizs = Quiz::all();
            break;
            case 'published':
                $quizs = Quiz::where('published', 1)->get();
            break;
            case 'pending':
                $quizs = Quiz::where('published', 0)->get();
            break;
            case 'deleted':
                $quizs = Quiz::onlyTrashed()->get();
            break;
            default:
                $quizs = Quiz::all();
        }

        $data = $this->getArrayData($quizs);

        $count = [
            'all' => Quiz::all()->count(),
            'published' => Quiz::where('published', 1)->count(),
            'pending' => Quiz::where('published', 0)->count(),
            'deleted' => Quiz::onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
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
            'user_id' => auth()->user()->id,
            'course_id' => $data['course_id'],
            'lesson_id' => $data['lesson_id'],
            'title' => $data['title'],
            'description' => $data['short_description'],
            'duration' => $data['duration'],
            'score' => $data['score']
        ];

        if(isset($data['model_id']) && ($data['model_id'] != -1)) {
            try {
                quiz::find($data['model_id'])->update($quiz_data);
    
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
        } else {
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
        
    }

    /**
     * Edit a quiz
     */
    public function edit($id) {

        $courses = Course::all();
        $quiz = quiz::find($id);
        return view('backend.quiz.edit', compact('courses', 'quiz'));
    }

    /**
     * Update a quiz
     */
    public function update(Request $request, $id) {

        $updateData = [
            'course_id' => $request->course_id,
            'lesson_id' => $request->lesson_id,
            'duration' => $request->duration,
            'score' => $request->score,
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
            
            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded-circle">' . substr($quiz->course->title, 0, 2) . '</span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-lead">'
                                                . $quiz->course->title . '</strong></p>
                                                <small class="js-lists-values-email text-50">'. $quiz->course->teachers[0]->name .'</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

            $temp['lesson'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . substr($quiz->lesson->title, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $quiz->lesson->title . '</strong></p>
                                            <small class="js-lists-values-email text-50">'. $quiz->course->teachers[0]->name .'</small>
                                        </div>
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
            $publish_route = route('admin.quizs.publish', $quiz->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($quiz->published == 0) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            }

            if($quiz->trashed()) {
                $restore_route = route('admin.test.restore', $quiz->id);
                $btn_restore = '<a href="'. $restore_route. '" class="btn btn-primary btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Restore"><i class="material-icons">arrow_back</i></a>';

                $forever_delete_route = route('admin.quizs.foreverDelete', $quiz->id);

                $perment_delete = '<a href="'. $forever_delete_route. '" class="btn btn-accent btn-sm" data-action="restore" data-toggle="tooltip"
                data-original-title="Delete Forever"><i class="material-icons">delete_forever</i></a>';

                $temp['action'] = $btn_restore . '&nbsp;' . $perment_delete;
            } else {
                if(auth()->user()->hasRole('Administrator')) {
                    $temp['action'] = $btn_edit . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
                } else {
                    $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;
                }
            }

            array_push($data, $temp);
        }

        return $data;
    }

    /**
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $quiz = Quiz::find($id);
        if($quiz->published == 1) {
            $quiz->published = 0;
        } else {
            $quiz->published = 1;
        }

        $quiz->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $quiz->published
        ]);
    }

    /**
     * Restore a Quiz
     */
    public function restore($id)
    {
        try {
            Quiz::withTrashed()->find($id)->restore();

            return response()->json([
                'success' => true,
                'action' => 'restore'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete Forever
     */
    public function foreverDelete($id)
    {
        try {

            Quiz::withTrashed()->where('id', $id)->forceDelete();

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
}
