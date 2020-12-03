<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Assignment;
use App\Models\AssignmentResult;
use App\Models\Course;
use App\Models\Lesson;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Jobs\SendEmail;
use App\User;

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
     *  Show assignment
     */
    public function show($id)
    {
        return view('backend.assignments.show');
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

        $data = $request->all();
        $assignment = Assignment::create($data);

        // Attachment
        if(isset($data['attachment'])) {
            $attachment = $request->file('attachment');
            $attachment_url = $this->saveFile($attachment);
            $assignment->attachment = $attachment_url;
        }
        $assignment->user_id = auth()->user()->id;

        $assignment->save();

        // Send Email to Students
        $student_ids = DB::table('course_student')->where('course_id', $assignment->course_id)->pluck('user_id');
        $student_emails = User::whereIn('id', $student_ids)->pluck('email');
        $email_data = [
            'template_type' => 'New_Assignment_Setup_By_Instructor',
            'mail_data' => [
                'model_type' => Assignment::class,
                'model_id' => $assignment->id
            ]
        ];

        foreach($student_emails as $email) {
            $email_data['mail_data']['email'] = $email;
            SendEmail::dispatch($email_data);
        }

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

        // Document
        if(!empty($data['attachment'])) {
            $attachment = $request->file('attachment');

            // Delete existing file
            if (File::exists(public_path('/storage/attachments/' . $assignment->attachment))) {
                File::delete(public_path('/storage/attachments/' . $assignment->attachment));
            }

            $attachment_url = $this->saveFile($attachment);
            $data['attachment'] = $attachment_url;
        } else {
            unset($data['attachment']);
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
            $temp['index'] = '';
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
            $show_route = route('student.assignment.show', [$item->lesson->slug, $item->id]);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
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
                $btn_restore = '<a href="'. $restore_route. '" class="btn btn-primary btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Restore"><i class="material-icons">arrow_back</i></a>';

                $forever_delete_route = route('admin.assignment.foreverDelete', $item->id);

                $perment_delete = '<a href="'. $forever_delete_route. '" class="btn btn-accent btn-sm" data-action="restore" data-toggle="tooltip"
                data-original-title="Delete Forever"><i class="material-icons">delete_forever</i></a>';

                $temp['action'] = $btn_restore . '&nbsp;' . $perment_delete;
            } else {
                if(auth()->user()->hasRole('Administrator')) {
                    $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
                } else {
                    $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
                }
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
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $assignment_ids = AssignmentResult::where('user_id', auth()->user()->id)->pluck('assignment_id');

        $count = [
            'all' => Assignment::whereIn('lesson_id', $lesson_ids)->count(),
            'marked' => Assignment::whereIn('id', $assignment_ids)->count()
        ];

        return view('backend.assignments.student', compact('count'));
    }

    public function getStudentAssignmentsByAjax($type)
    {
        // Get purchased Course IDs
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_ids = Course::whereIn('id', $course_ids)->where('end_date', '>', Carbon::now()->format('Y-m-d'))->pluck('id');
        $lesson_ids = Lesson::whereIn('course_id', $course_ids)->pluck('id');
        $assignment_ids = AssignmentResult::where('user_id', auth()->user()->id)->pluck('assignment_id');

        switch($type) {

            case 'all':
                $assignments = Assignment::whereIn('lesson_id', $lesson_ids)->get();
            break;

            case 'marked':
                $assignments = Assignment::whereIn('id', $assignment_ids)->get();
            break;

        }

        $data = $this->getStudentData($assignments);

        $count = [
            'all' => Assignment::whereIn('lesson_id', $lesson_ids)->count(),
            'marked' => Assignment::whereIn('id', $assignment_ids)->count()
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

            $temp['index'] = '';
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'. substr($item->title, 0, 2) .'</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>'. $item->title .'</strong></small>
                                        <small class="text-70">
                                            Course: '. $lesson->course->title .' |
                                            Lesson: '. $lesson->title .'
                                        </small>
                                    </div>
                                </div>
                            </div>';

            $temp['due'] = '<strong>' . $item->due_date . '</strong>';
            $temp['mark'] = '<strong>' . $item->total_mark . '</strong>';

            if($item->result && $item->result->count() > 0) {
                $btn_show = '<a href="'. route('student.assignment.result', [$lesson->slug, $item->id]). '" class="btn btn-success btn-sm">Review</a>';
            } else {
                $btn_show = '<a href="'. route('student.assignment.show', [$lesson->slug, $item->id]). '" class="btn btn-primary btn-sm">Start</a>';
            }

            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return $data;
    }

    public function submitedAssignments()
    {
        $assignments = Assignment::where('user_id', auth()->user()->id)->get();
        $assignment_ids = Assignment::where('user_id', auth()->user()->id)->pluck('id');
        $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids);

        $count = [
            'all' => $assignment_results->count(),
            'marked' => $assignment_results->whereNotNull('mark')->count()
        ];

        return view('backend.assignments.teacher', compact('count'));
    }

    public function getSubmitedAssignmentsByAjax($type)
    {
        $assignments = Assignment::where('user_id', auth()->user()->id)->get();
        $assignment_ids = Assignment::where('user_id', auth()->user()->id)->pluck('id');

        $count = [
            'all' => AssignmentResult::whereIn('assignment_id', $assignment_ids)->count(),
            'marked' => AssignmentResult::whereIn('assignment_id', $assignment_ids)->whereNotNull('mark')->count()
        ];

        switch($type)
        {
            case 'all':
                $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids)->orderBy('submit_date', 'desc')->get();
            break;

            case 'marked':
                $assignment_results = AssignmentResult::whereIn('assignment_id', $assignment_ids)->orderBy('submit_date', 'desc')->whereNotNull('mark')->get();
            break;
        }

        $data = [];
        foreach($assignment_results as $result) {
            $temp = [];
            $temp['index'] = '';
            $temp['subject'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded bg-primary text-white">
                                            '. substr($result->assignment->title, 0, 2) .'
                                        </span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-project">
                                                <strong>'. $result->assignment->title .'</strong>
                                            </small>
                                            <small class="text-70">
                                                Course: '. $result->assignment->lesson->course->title .' | Lesson: '. $result->assignment->lesson->title .'
                                            </small>
                                        </div>
                                    </div>
                                </div>';

            if(!empty($result->user->avatar)) {
                $avatar = '<img src="'. asset('/storage/avatars/' . $result->user->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($result->user->name, 0, 2) .'</span>';
            }

            $temp['student'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        '. $avatar .'
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-name">'. $result->user->name .'</strong></p>
                                                <small class="js-lists-values-email text-50">'. $result->user->email .'</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

            
            $ext = pathinfo($result->attachment_url, PATHINFO_EXTENSION);
            if(!empty($result->attachment_url)) {
                if($ext == 'pdf') {
                    $img = '<img class="img-fluid rounded w-50" src="'. asset('/images/pdf.png') .'" alt="image">';
                } else {
                    $img = '<img class="img-fluid rounded w-50" src="'. asset('/images/docx.png') .'" alt="image">';
                }
                $temp['attachment'] = '<a href="'. asset('/storage/attachments/' . $result->attachment_url ) .'" target="_blank">'. $img .'</a>';
            } else {
                $temp['attachment'] = 'N/A';
            }

            $btn_show = view('backend.buttons.show', ['show_route' => route('admin.assignments.show_result', $result->id)]);
            
            $temp['action'] = $btn_show . '&nbsp;';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    /**
     * Show Result of Assignment
     */
    public function show_result($id)
    {
        $result = AssignmentResult::find($id);
        return view('backend.assignments.result', compact('result'));
    }

    /**
     * Answer of Assignment Answer
     */
    public function result_answer(Request $request)
    {
        $data = $request->all();
        $result = AssignmentResult::find($data['result_id']);

        if(!empty($data['answer_attach'])) {
            $attachment = $request->file('answer_attach');

            // Delete existing file
            if (File::exists(public_path('/storage/attachments/' . $result->answer_attach))) {
                File::delete(public_path('/storage/attachments/' . $result->answer_attach));
            }

            $attachment_url = $this->saveFile($attachment);
            $data['answer_attach'] = $attachment_url;
        }
        
        $result->mark = $data['mark'];
        $result->answer = $data['answer'];
        $result->answer_attach = $data['answer_attach'];
        $result->status = 1;
        $result->submit_date = Carbon::now();

        $result->save();

        return response()->json([
            'success' => true,
            'action' => 'update'
        ]);
    }

    /**
     * Delete Forever
     */
    public function foreverDelete($id)
    {
        try {

            Assignment::withTrashed()->where('id', $id)->forceDelete();

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
