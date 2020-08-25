<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Traits\FileUploadTrait;
use DB;
use Illuminate\Support\Facades\File;

use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Level;

use App\Services\ColorService;
use App\Services\CalendarService;

class CourseController extends Controller
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
     *  Show all of Courses
     */
    public function index() {

        $count = [
            'all' => Course::all()->count(),
            'published' => Course::where('published', 1)->count(),
            'pending' => Course::where('published', 0)->count(),
            'deleted' => Course::onlyTrashed()->count()
        ];

        return view('backend.course.index', compact('count'));
    }

    public function browse() {
        $parentCategories = Category::where('parent', 0)->get();
        $popular_courses = Course::where('popular', 1)->orderBy('created_at', 'desc')->limit(8)->get();
        $trending_courses = Course::where('trending', 1)->orderBy('created_at', 'desc')->limit(8)->get();
        $featured_courses = Course::where('featured', 1)->orderBy('created_at', 'desc')->limit(8)->get();
        return view(
            'backend.course.student.index', 
            compact(
                'parentCategories',
                'popular_courses',
                'trending_courses',
                'featured_courses'
            )
        );
    }

    /**
     * Show Selected Course
     */
    public function show($id) {

        // Show course
    }

    /**
     * List data for Datatable
     */
    public function getList($type) {

        switch ($type) {
            case 'all':
                $courses = Course::all();
            break;
            case 'published':
                $courses = Course::where('published', 1)->get();
            break;
            case 'pending':
                $courses = Course::where('published', 0)->get();
            break;
            case 'deleted':
                $courses = Course::onlyTrashed()->get();
            break;
            default:
                $courses = Course::all();
        }

        $data = $this->getArrayData($courses);
        $count = [
            'all' => Course::all()->count(),
            'published' => Course::where('published', 1)->count(),
            'pending' => Course::where('published', 0)->count(),
            'deleted' => Course::onlyTrashed()->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
    }

    /**
     * Create a Course.
     */ 
    public function create() {
        $parentCategories = Category::where('parent', 0)->get();

        $tags = DB::table('tags')->get();
        $levels = Level::where('parent', $parentCategories[0]->level_id)->get();

        return view('backend.course.create', compact('parentCategories', 'levels', 'tags'));
    }

    /**
     * Store new course data
     */
    public function store(Request $request) {

        $data = $request->all();

        // Title
        $title = (empty($data['title'])) ? 'Untitled Course' : $data['title'];

        if(!isset($data['tags'])) {
            $data['tags'] = ['Default'];
        }

        // Set tags
        foreach($data['tags'] as $item) {
            $count = DB::table('tags')->where('name', $item)->count();
            if($count < 1) {
                DB::table('tags')->insert($item);
            }
        }

        // Course Data
        $course_data = [
            'category_id' => $data['category'],
            'title' => $title,
            'slug' => str_slug($title),
            'short_description' => $data['short_description'],
            'description' => $data['course_description'],
            'level_id' => $data['level'],
            'tags' => $data['tags'],
            'private_price' => $data['private_price'],
            'group_price' => $data['group_price'],
            'timezone' => $data['timezone'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'repeat' => $data['repeat'],
            'repeat_value' => $data['repeat_value'],
            'repeat_type' => $data['repeat_type'],
            'min' => $data['min'],
            'max' => $data['max'],
            'style' => rand(0, 10)
        ];

        // Course image
        if(!empty($data['course_image'])) {
            $image = $request->file('course_image');
            $course_image_url = $this->saveImage($image, 'upload', true);
            $course_data['course_image'] = $course_image_url;
        }

        // Create Media
        if(!empty($data['course_video'])) {

            // Add Media
            $video_id = array_last(explode('/', $data['course_video']));

            $media_data = [
                'model_type' => 'App\Models\Course',
                'name' => $data['title'] . ' - Video',
                'url' => $data['course_video'],
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];
        }

        $message = '';
        $course_id = (!empty($data['course_id'])) ? $data['course_id'] : '';

        if(empty($course_id)) {
            try {
                $course = Course::create($course_data);
                $course_id = $course->id;

                // Add teacher to this course (me)
                DB::table('course_user')->insert([
                    'course_id' => $course_id,
                    'user_id' => auth()->user()->id
                ]);

                if(!empty($media_data)) {
                    $media_data['model_id'] = $course_id;
                    $media = Media::create($media_data);
                }

            } catch(Exception $e) {
                $message .= $e->getMessage();
            }

        } else {

            try {
                $rlt = Course::find($course_id)->update($course_data);

                // Update Media
                $media = Course::where('model_type', 'App\Models\Course')
                    ->where('model_id', $course_id)->first();

                if(!empty($media_data)) {
                    if(empty($media)) {
                        $media_data['model_id'] = $course_id;
                        $media = Media::create($media_data);
                    } else {
                        $media::update($media_data);
                    }
                }
                
            } catch(Exception $e) {
                $message .= $e->getMessage();
            }
        }

        if($request->send_type == 'submit') {
            return redirect()->route('admin.courses.edit', $course_id);
        }

        if($request->send_type == 'ajax') {

            if(empty($message)) {
                return response()->json([
                    'success' => true,
                    'course_id' => $course_id
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
        }
    }

    /**
     * Edit course
     */
    public function edit($id, CalendarService $calendarService)
    {
        $course = Course::find($id);
        $parentCategories = Category::where('parent', 0)->get();
        $tags = DB::table('tags')->get();
        $category = Category::find($course->category_id);
        $levels = Level::where('parent', '0')->get();
        if(!empty($category)) {
            $levels = Level::where('parent', $category->level_id)->get();
        }
        
        $schedules = $calendarService->getOnePeriodSchedule($id);
        
        return view('backend.course.edit', compact('course', 'parentCategories', 'tags', 'levels', 'schedules'));
    }

    /**
     * Update Course
     */
    public function update(Request $request, $id, ColorService $colorService) {

        $course = Course::find($id);

        $data = $request->all();

        if(!isset($data['tags'])) {
            $data['tags'] = ['Default'];
        }

        // Set tags
        foreach($data['tags'] as $item) {
            $count = DB::table('tags')->where('name', $item)->count();
            if($count < 1) {
                DB::table('tags')->insert($item);
            }
        }

        // Course Data
        $course_data = [
            'category_id' => $data['category'],
            'title' => $data['title'],
            'slug' => str_slug($data['title']),
            'short_description' => $data['short_description'],
            'description' => $data['course_description'],
            'level_id' => $data['level'],
            'tags' => $data['tags'],
            'private_price' => $data['private_price'],
            'group_price' => $data['group_price'],
            'timezone' => $data['timezone'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'repeat' => $data['repeat'],
            'repeat_value' => $data['repeat_value'],
            'repeat_type' => $data['repeat_type'],
            'min' => $data['min'],
            'max' => $data['max']
        ];

        // Course image
        if(!empty($data['course_image'])) {
            $image = $request->file('course_image');

            // Delete existing img file
            if (File::exists(public_path('/storage/uploads/' . $course->course_image))) {
                File::delete(public_path('/storage/uploads/' . $course->course_image));
                File::delete(public_path('/storage/uploads/thumb/' . $course->course_image));
            }

            $course_image_url = $this->saveImage($image, 'upload', true);
            $course_data['course_image'] = $course_image_url;
        }

        try {
            $course->update($course_data);
        } catch (Exception $e) {
            $error = $e->getMessage();
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }
 
        // Update Course Media - Course video
        if(!empty($data['course_video'])) {
            $video_id = array_last(explode('/', $data['course_video']));
            $media_data = [
                'model_type' => 'App\Models\Course',
                'name' => $data['title'] . ' - Video',
                'url' => $data['course_video'],
                'type' => 'video',
                'file_name' => $video_id,
                'size' => 0
            ];
    
            $media = Media::where('model_type', 'App\Models\Course')
                ->where('model_id', $id)->first();

            if(empty($media)) {
                $media_data['model_id'] = $id;
                $media = Media::create($media_data);
            } else {
    
                try {
                    Media::where('model_type', 'App\Models\Course')
                        ->where('model_id', $id)->update($media_data);
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }
            }
        }
        
        // Update Tags
        $tags = DB::table('tags')->get();
        $tags_array = [];
        foreach($tags as $tag) {
            array_push($tags_array, $tag->name);
        }

        $differenceTags = array_diff($data['tags'], $tags_array);

        if(!empty($differenceTags)) {
            foreach($data['tags'] as $tag) {
                DB::table('tags')->updateOrInsert(
                    ['name' => $tag],
                    ['name' => $tag]
                );
            }
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id) {

        try {
            Course::find($id)->delete();

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
            Course::withTrashed()->find($id)->restore();

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
     * Publish or Unpublish
     */
    public function publish($id)
    {
        $course = Course::find($id);
        if($course->published == 1) {
            $course->published = 0;
        } else {
            $course->published = 1;
        }

        $course->save();

        return response()->json([
            'success' => true,
            'action' => 'publish',
            'published' => $course->published
        ]);
    }

    public function getArrayData($courses) {
        $data = [];
        $i = 0;

        foreach($courses as $course) {
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
                                        . substr($course->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $course->title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $course->slug .'</small>
                                    </div>
                                </div>
                            </div>';
            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded-circle">' . substr($course->teachers[0]->name, 0, 2) . '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'
                                            . $course->teachers[0]->name . '</strong></p>
                                            <small class="js-lists-values-email text-50">Teacher</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            
            if(!empty($course->category))
                $temp['category'] = $course->category->name;
            else 
                $temp['category'] = 'No Category';

            if($course->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }

            $show_route = route('courses.show', $course->slug);
            $edit_route = route('admin.courses.edit', $course->id);
            $delete_route = route('admin.courses.destroy', $course->id);
            $publish_route = route('admin.courses.publish', $course->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($course->published == 0) {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-success btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="Publish"><i class="material-icons">arrow_upward</i></a>';
            } else {
                $btn_publish = '<a href="'. $publish_route. '" class="btn btn-info btn-sm" data-action="publish" data-toggle="tooltip"
                    data-title="UnPublish"><i class="material-icons">arrow_downward</i></a>';
            }

            if($course->trashed()) {
                $restore_route = route('admin.courses.restore', $course->id);
                $btn_delete = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Recover"><i class="material-icons">restore_from_trash</i></a>';
            }

            if(auth()->user()->hasRole('Administrator')) {
                $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_publish . '&nbsp;' . $btn_delete;
            } else {
                $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
            }

            array_push($data, $temp);
        }

        return $data;
    }
}
