<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\Traits\FileUploadTrait;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Schedule;
use App\Models\Step;
use App\Models\Test;

class LessonController extends Controller
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

    public function index() {
        //
    }

    /**
     * Show a lesson
     */
    public function show(Request $request, $id) {

        $lesson = Lesson::find($id);
        $step = Step::find($request->step);
        $next = Step::where('lesson_id', $id)->where('step', $step->step + 1)->first();
        return view('backend.lesson.show', compact('lesson', 'step', 'next'));
    }

    /**
     * Store a Lesson
     */
    public function store(Request $request) {
        
        $data = $request->all();

        $lesson_data = [
            'course_id' => $data['course_id'],
            'title' => $data['lesson_title'],
            'slug' => str_slug($data['lesson_title']),
            'video' => $data['lesson_intro_video'],
            'short_text' => $data['lesson_short_description']
        ];

        if(!empty($data['lesson_file_image'])) {
            $image = $request->file('lesson_file_image');
            $lesson_image_url = $this->saveImage($image);
            $lesson_data['image'] = $lesson_image_url;
        }

        if($data['action'] == 'new') {

            // Get last lesson position
            $position = 1;
            $last_lesson = Lesson::where('course_id', $data['course_id'])->orderBy('position', 'desc')->first();
            if(!empty($last_lesson)) {
                $position = (int)$last_lesson->position + 1;
            }

            $lesson_data['position'] = $position;

            try {

                $lesson = Lesson::create($lesson_data);
                if(!empty($data['lesson_schedule'])) {
                    $schedule = Schedule::find($data['lesson_schedule']);
                    $schedule->lesson_id = $lesson->id;
                    $schedule->save();
                }
    
                // step Data
                $stepData = $this->stepData($data);

                foreach($stepData as $step) {
                    $new_data = [
                        'lesson_id' => $lesson->id,
                        'type' => $step['type'],
                        'step' => $step['step']
                    ];

                    $new_data['title'] = empty($step['title']) ? 'Untitled' : $step['title'];
                    
                    $new_data[$step['type']] = $step['content'];
                    Step::create($new_data);

                    if($new_data[$step['type']] == 'test') {
                        $test_id = $step['content'];
                        Test::find($test_id)->update(['lesson_id' => $lesson->id]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'lesson' => $lesson,
                    'action' => 'new'
                ]);

            } catch(Exception $e) {
                $error = $e->getMessage();
                return response()->json([
                    'success' => false,
                    'message' => $error
                ]);
            }
        }

        if($data['action'] == 'edit') {

            try {
                $lesson = Lesson::find($data['lesson_id'])->update($lesson_data);
                if(!empty($data['lesson_schedule'])) {
                    $schedule = Schedule::find($data['lesson_schedule']);
                    $schedule->lesson_id = $lesson->id;
                    $schedule->save();
                }

                // step Data
                $stepData = $this->stepData($data);

                foreach($stepData as $step) {

                    if($step['id'] == '') {
                        $new_data = [
                            'lesson_id' => $data['lesson_id'],
                            'type' => $step['type'],
                            'step' => $step['step']
                        ];

                        $new_data['title'] = empty($step['title']) ? 'Untitled' : $step['title'];
                        $new_data[$step['type']] = $step['content'];
    
                        Step::create($new_data);
                    } else {
                        $update_data = [];
                        $update_data[$step['type']] = $step['content'];
                        $title = empty($step['title']) ? 'Untitled' : $step['title'];
                        $update_data['title'] = $title;
                        $editStep = Step::find($step['id'])->update($update_data);
                    }

                    if($step['type'] == 'test') {
                        $test_id = $step['content'];
                        Test::find($test_id)->update(['lesson_id' => $data['lesson_id']]);
                    }
                }

                return response()->json([
                    'success' => true,
                    'action' => 'edit'
                ]);

            } catch(Exception $e) {
                $error = $e->getMessage();
                return response()->json([
                    'success' => false,
                    'message' => $error
                ]);
            }
        }
        
    }

    /**
     * Delete a lesson
     */
    public function delete($id) {

        try {
            Lesson::find($id)->delete();

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
     * Get Lesson by Id
     */
    public function getLesson($id) {

        $lesson = Lesson::find($id);
        $schedule = Schedule::where('lesson_id', $id)->first();
        $steps = DB::table('lesson_steps')->where('lesson_id', $id)->get();

        return response()->json([
            'success' => true,
            'lesson' => $lesson,
            'schedule' => $schedule,
            'steps' => $steps
        ]);
    }

    function stepData($data) {

        $stepData = [];

        foreach($data as $key => $content) {

            if(preg_match('/__/', $key) && !preg_match('/id__/', $key) && !preg_match('/title__/', $key)) {

                $itemData = [
                    'content' => $content,
                    'type' => 'text',
                    'id' => '',
                    'step' => 1,
                ];

                $itemData['step'] = (int)substr($key, strpos($key, '__') + 2);

                if(preg_match('/lesson_description__/', $key)) {
                    $itemData['type'] = 'text';
                    $itemData['id'] = $this->getValue($data, '/lesson_description_id__' . $itemData['step'] . '/');
                    $itemData['title'] = $this->getValue($data, '/lesson_description_title__'. $itemData['step'] .'/');
                }
                if(preg_match('/lesson_video__/', $key)) {
                    $itemData['type'] = 'video';
                    $itemData['id'] = $this->getValue($data, '/lesson_video_id__' . $itemData['step'] . '/');
                    $itemData['title'] = $this->getValue($data, '/lesson_video_title__' . $itemData['step'] . '/');
                }
                if(preg_match('/test__/', $key)) {
                    $itemData['type'] = 'test';
                    $itemData['id'] = $this->getValue($data, '/test_id__' . $itemData['step'] . '/');
                    $itemData['title'] = $this->getValue($data, '/test_title__' . $itemData['step'] . '/');
                }
                
                array_push($stepData, $itemData);
            }
        }

        return $stepData;
    }

    function getValue($data, $regex) {

        foreach($data as $key => $item) {
            if(preg_match($regex, $key)) {
                return $item;
            }
        }

        return '';
    }
}
