<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class AssignmentsController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Assignments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = [
            'all' => Assignment::all()->count(),
            'published' => Assignment::where('published', 1)->count(),
            'pending' => Assignment::where('published', 0)->count(),
            'deleted' => Assignment::onlyTrashed()->count()
        ];

        return view('backend.assignments.index', compact('count'));
    }

    /**
     * Create a new Assignment
     */
    public function create()
    {
        $courses = Course::all();
        return view('backend.assignments.create', compact('courses'));
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
        $assignment = Assignment::create($data);

        // Attachment
        if(isset($data['attachment'])) {
            $attachment = $request->file('attachment');
            $attachment_url = $this->saveImage($attachment, 'upload', true);
            $assignment->attachment = $attachment_url;
        }
        $assignment->user_id = auth()->user()->id;

        $assignment->save();

        return response()->json([
            'success' => true,
            'assignment_id' => $assignment->id
        ]);
    }

    /**
     * Edit Assignment
     */
    public function edit($id)
    {
        $assignment = Assignment::find($id);
        $courses = Course::all();
        return view('backend.assignments.edit', compact('assignment', 'courses'));
    }

    /**
     * Update Assignment
     */
    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);

        $data = $request->all();

        // Bundle image
        if(!empty($data['attachment'])) {
            $attachment = $request->file('attachment');

            // Delete existing file
            if (File::exists(public_path('/storage/uploads/' . $assignment->attachment))) {
                File::delete(public_path('/storage/uploads/' . $assignment->attachment));
                File::delete(public_path('/storage/uploads/thumb/' . $assignment->attachment));
            }

            $attachment_url = $this->saveImage($attachment, 'upload', true);
            $data['attachment'] = $attachment_url;
        }

        try {
            $assignment->update($data);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        switch ($type) {
            case 'all':
                $assignments = Assignment::all();
            break;
            case 'published':
                $assignments = Assignment::where('published', 1)->get();
            break;
            case 'pending':
                $assignments = Assignment::where('published', 0)->get();
            break;
            case 'deleted':
                $assignments = Assignment::onlyTrashed()->get();
            break;
            default:
                $assignments = Assignment::all();
        }

        $data = $this->getArrayData($assignments);

        $count = [
            'all' => Assignment::all()->count(),
            'published' => Assignment::where('published', 1)->count(),
            'pending' => Assignment::where('published', 0)->count(),
            'deleted' => Assignment::onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    /**
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $assignment = Assignment::find($id);
        if($assignment->published == 1) {
            $assignment->published = 0;
        } else {
            $assignment->published = 1;
        }

        $assignment->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $assignment->published
        ]);
    }

    public function destroy($id) {

        try {
            Assignment::find($id)->delete();

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

    public function restore($id) {

        try {
            Assignment::withTrashed()->find($id)->restore();

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

    public function getArrayData($assignments) {
        $data = [];
        $i = 0;

        foreach($assignments as $item) {
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

            $edit_route = route('admin.assignments.edit', $item->id);
            $delete_route = route('admin.assignments.destroy', $item->id);
            $publish_route = route('admin.assignment.publish', $item->id);

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
                $restore_route = route('admin.assignment.restore', $item->id);
                $btn_delete = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Recover"><i class="material-icons">restore_from_trash</i></a>';
            }

            if(auth()->user()->hasRole('Administrator')) {
                $temp['action'] = $btn_edit . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
            } else {
                $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;
            }

            array_push($data, $temp);
        }

        return $data;
    }

    // Student Dashboard
    public function studentAssignments()
    {
        // Get purchased Course IDs
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->get();

        $count = [
            'all' => Assignment::whereIn('lesson_id', $lesson_ids)->count(),
            'deleted' => Assignment::whereIn('lesson_id', $lesson_ids)->onlyTrashed()->count()
        ];

        return view('backend.assignments.student', compact('count'));
    }

    public function getStudentAssignmentsByAjax($type)
    {
        // Get purchased Course IDs
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');

        switch($type) {

            case 'all':
                $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->get();
            break;

            case 'deleted':
                $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->onlyTrashed()->get();
            break;

        }

        $data = $this->getStudentData($assignments);

        $count = [
            'all' => Assignment::whereIn('lesson_id', $lesson_ids)->count(),
            'deleted' => Assignment::whereIn('lesson_id', $lesson_ids)->onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    public function getStudentData($assignments)
    {
        $data = [];
        foreach($assignments as $item) {
            $lesson = Lesson::find($item->lesson->id);
            $course = $lesson->course;
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'. substr($item->title, 0, 2) .'</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>'. $item->title .'</strong></small>
                                        <small class="text-70">
                                            Course: '. $item->lesson->course->title .' |
                                            Lesson: '. $item->lesson->title .'
                                        </small>
                                    </div>
                                </div>
                            </div>';

            $temp['due'] = '<strong>' . $item->due_date . '</strong>';
            $temp['mark'] = '<strong>' . $item->total_mark . '</strong>';

            $show_route = route('lesson.assignment', $item->id);
            $btn_show = '<a href="'. $show_route. '" class="btn btn-success btn-sm">View</a>';

            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return $data;
    }
}
