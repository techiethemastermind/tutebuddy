<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Test;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Question;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Tests.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = [
            'all' => Test::all()->count(),
            'published' => Test::where('published', 1)->count(),
            'pending' => Test::where('published', 0)->count(),
            'deleted' => Test::onlyTrashed()->count()
        ];

        return view('backend.tests.index', compact('count'));
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        switch ($type) {
            case 'all':
                $tests = Test::all();
            break;
            case 'published':
                $tests = Test::where('published', 1)->get();
            break;
            case 'pending':
                $tests = Test::where('published', 0)->get();
            break;
            case 'deleted':
                $tests = Test::onlyTrashed()->get();
            break;
            default:
                $tests = Test::all();
        }

        $data = $this->getArrayData($tests);

        $count = [
            'all' => Test::all()->count(),
            'published' => Test::where('published', 1)->count(),
            'pending' => Test::where('published', 0)->count(),
            'deleted' => Test::onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getArrayData($tests) {
        $data = [];
        $i = 0;

        foreach($tests as $item) {
            $lesson = Lesson::find($item->lesson->id);
            $course = $lesson->course;
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
                                        . substr($item->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $item->title . '</strong></small>
                                    </div>
                                </div>
                            </div>';
            $temp['course'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . substr($course->title, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $course->title . '</strong></p>
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['lesson'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                            <div class="avatar avatar-sm mr-8pt">
                                <span class="avatar-title rounded-circle">' . substr($lesson->title, 0, 2) . '</span>
                            </div>
                            <div class="media-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex d-flex flex-column">
                                        <p class="mb-0"><strong class="js-lists-values-lead">'
                                        . $lesson->title . '</strong></p>
                                        <small class="js-lists-values-email text-50">Teacher</small>
                                    </div>
                                </div>
                            </div>
                        </div>';

            $edit_route = route('admin.tests.edit', $item->id);
            $delete_route = route('admin.tests.destroy', $item->id);
            $publish_route = route('admin.test.publish', $item->id);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($item->published == 0) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            }

            if($item->trashed()) {
                $restore_route = route('admin.test.restore', $item->id);
                $btn_restore = '<a href="'. $restore_route. '" class="btn btn-primary btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Restore"><i class="material-icons">arrow_back</i></a>';

                $forever_delete_route = route('admin.test.foreverDelete', $item->id);

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
     * Return Lessons html by Option tag for selected course
     */
    public function getLessons(Request $request) {

        $lessons = Lesson::where('course_id', $request->course_id)->get();

        $html = '';

        foreach($lessons as $lesson) {
            if(strlen($lesson->short_text) > 60) {
                $lesson_desc = substr($lesson->short_text, 0, 60) . '...';
            } else {
                $lesson_desc = $lesson->short_text;
            }
            if(isset($request->lesson_id) && $request->lesson_id == $lesson->id) {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc' selected>$lesson->title</option>";
            } else {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc'>$lesson->title</option>";
            }
        }

        return response()->json([
            'success' => true,
            'options' => $html
        ]);
    }

    /**
     * Create a new Test
     */
    public function create()
    {
        $courses = Course::all();
        return view('backend.tests.create', compact('courses'));
    }

    /**
     * Store new Assignment
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'lesson_id' => 'required'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        if(!empty($data['test_id'])) {
            $test = Test::find($data['test_id']);
            $test->update($data);
        } else {
            $test = Test::create($data);
        }

        return response()->json([
            'success' => true,
            'test_id' => $test->id
        ]);
    }

    /**
     * Edit Assignment
     */
    public function edit($id)
    {
        $test = Test::find($id);
        $courses = Course::all();
        return view('backend.tests.edit', compact('test', 'courses'));
    }

    /**
     * Update Test
     */
    public function update(Request $request, $id)
    {
        $test = Test::find($id);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        
        try {
            $test->update($data);

            return response()->json([
                'success' => true,
                'action' => 'update'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Delete a Test
     */
    public function destroy($id)
    {
        try {
            Test::find($id)->delete();

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
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $test = Test::find($id);
        if($test->published == 1) {
            $test->published = 0;
        } else {
            $test->published = 1;
        }

        $test->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $test->published
        ]);
    }

    /**
     * Restore a Test
     */
    public function restore($id) {

        try {
            Test::withTrashed()->find($id)->restore();

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
            
            $questions = Question::where('model_id', $id)->where('model_type', Test::class)->get();
            foreach($questions as $question) {
                $question->forceDelete();
            }

            Test::withTrashed()->where('id', $id)->forceDelete();

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
