<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Lesson;

use Carbon\Carbon;

use App\Services\CalendarService;
use App\Services\ColorService;

class ScheduleController extends Controller
{
    public function index() {
        $courses = Course::where('published', 1)->get();
        return view('backend.schedule.index', compact('courses'));
    }

    public function getScheduleData(CalendarService $calendarService, ColorService $colorService, Request $request) {

        $data = $request->all();
        $weekly_schedule_data = $calendarService->generateCalendarData($data, $colorService);

        return response()->json([
            'data' => $weekly_schedule_data
        ]);
    }

    public function storeSchedule(Request $request) {

        $request_start = timezone()->convertFromLocal(Carbon::parse($request->start)->format('Y-m-d H:i:s'));
        $request_end = timezone()->convertFromLocal(Carbon::parse($request->end)->format('Y-m-d H:i:s'));

        $base_date = $request_start->format('Y-m-d');
        $start_time = $request_start->format('H:i:s');
        $end_time = $request_end->format('H:i:s');

        $new_data = [
            'course_id' => $request->id,
            'date' => $base_date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'timezone' => $request->timezone
        ];

        $schedule = Schedule::create($new_data);

        return response()->json([
            'success' => true,
            'schedule_id' => $schedule->id
        ]);
    }

    /**
     * Return Lessons html by Option tag for selected course
     */
    public function getLessons(Request $request) {

        $schedule = Schedule::find($request->id);

        $course = $schedule->course;
        $course_id = $course->id;
        $course_title = $course->title;
        $lessons = Lesson::where('course_id', $course_id)->get();

        $html = '';

        foreach($lessons as $lesson) {
            if(strlen($lesson->short_text) > 60) {
                $lesson_desc = substr($lesson->short_text, 0, 60) . '...';
            } else {
                $lesson_desc = $lesson->short_text;
            }
            if(!empty($schedule->lesson_id) && $schedule->lesson_id == $lesson->id) {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc' selected>$lesson->title</option>";
            } else {
                $html .= "<option value='$lesson->id' data-desc='$lesson_desc'>$lesson->title</option>";
            }
        }

        return response()->json([
            'success' => true,
            'options' => $html,
            'course_title' => $course_title,
            'lesson_id' => $schedule->lesson_id
        ]);
    }

    public function addLesson(Request $request) {

        $schedule = Schedule::find($request->id);
        $schedule->lesson_id = $request->lesson_id;
        $request_start = timezone()->convertFromLocal(Carbon::parse($request->start)->format('Y-m-d H:i:s'));
        $request_end = timezone()->convertFromLocal(Carbon::parse($request->end)->format('Y-m-d H:i:s'));
        $schedule->start_time = $request_start->format('H:i:s');
        $schedule->end_time = $request_end->format('H:i:s');

        try {
            $schedule->save();

            return response()->json([
                'success' => true,
                'action' => 'addLesson'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'action' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update Schedule
     */
    public function updateSchedule(Request $request) {

        $schedule = Schedule::find($request->id);
        $request_start = timezone()->convertFromLocal(Carbon::parse($request->start)->format('Y-m-d H:i:s'));
        $request_end = timezone()->convertFromLocal(Carbon::parse($request->end)->format('Y-m-d H:i:s'));

        $schedule->date = $request_start->format('Y-m-d');
        $schedule->start_time = $request_start->format('H:i:s');
        $schedule->end_time = $request_end->format('H:i:s');

        try {
            $schedule->save();

            return response()->json([
                'success' => true,
                'action' => 'addLesson'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'action' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete Schedule
     */
    public function deleteSchedule(Request $request) {

        try {
            Schedule::find($request->id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'delete'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'action' => $e->getMessage()
            ]);
        }
        
    }
}
