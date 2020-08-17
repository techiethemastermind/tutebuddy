<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Test;

class TestController extends Controller
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
     * List of Tests
     */
    public function index() {

        $courses = Course::all();
        return view('backend.test.index', compact('courses'));
    }

    /**
     * Get Tests by Course id
     */
    public function getList($id) {

        $tests = Test::where('course_id', $id)->get();
        $data = $this->getArrayData($tests);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Add new Test
     */
    public function create() {

        $courses = Course::all();
        return view('backend.test.create', compact('courses'));
    }

    /**
     * Store a Question
     */
    public function store(Request $request) {

        $data = $request->all();
        $test_data = [
            'course_id' => $data['course_id'],
            'title' => $data['title'],
            'description' => $data['test_description']
        ];
        
        try {
            $test = Test::create($test_data);

            return response()->json([
                'success' => true,
                'test' => $test
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
        
    }

    /**
     * Edit a Test
     */
    public function edit($id) {

        $courses = Course::all();
        $test = Test::find($id);
        return view('backend.test.edit', compact('test', 'courses'));
    }

    /**
     * Update a Test
     */
    public function update(Request $request, $id) {

        $updateData = [
            'course_id' => $request->course_id,
            'title' => $request->title,
            'description' => $request->short_description 
        ];

        try {
            Test::find($id)->update($updateData);

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
     * Delete Test
     */
    public function destroy($id) {

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

    function getArrayData($tests) {
        $data = [];
        $i = 0;

        foreach($tests as $test) {
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
                                        . substr($test->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $test->title . '</strong></small>
                                    </div>
                                </div>
                            </div>';

            $temp['questions'] = $test->questions->count();

            if(!empty($test->lesson_id)) {
                $temp['assigned'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">' . $test->lesson->name . '</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['assigned'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">No Assigned</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }

            $show_route = route('admin.tests.show', $test->id);
            $edit_route = route('admin.tests.edit', $test->id);
            $delete_route = route('admin.tests.destroy', $test->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($test->trashed()) {
                $restore_route = route('admin.tests.restore', $test->id);
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
