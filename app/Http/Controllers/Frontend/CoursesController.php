<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use DB;

class CoursesController extends Controller
{
    //
    public function index()
    {
        // List of Courses
    }

    /**
     * Show Selected Course
     */
    public function show ($slug)
    {
        $course = Course::where('slug', $slug)->first();

        $course_rating = 0;
        $total_ratings = 0;
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }

        $is_reviewed = false;
        if(auth()->check() && $course->reviews()->where('user_id', '=', auth()->user()->id)->first()){
            $is_reviewed = true;
        }
        $is_enrolled = auth()->check() && $course->students()->where('user_id', auth()->user()->id)->count() > 0;

        return view('frontend.course.course', compact('course', 'course_rating', 'total_ratings', 'is_reviewed', 'is_enrolled', 'courseDuration'));
    }

    public function addReview(Request $request, $id) {

        $course = Course::findORFail($id);

        $review_data = [
            'user_id' => auth()->user()->id,
            'reviewable_id' => $course->id,
            'reviewable_type' => Course::class,
            'rating' => $request->rating,
            'content' => $request->review
        ];

        try {
            $review = Review::create($review_data);
            return response()->json([
                'success' => true,
                'review' => $review
            ]);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function getSearchFormData($key)
    {

        $data = [];
        $categories = Category::where('name', 'like', '%' . $key . '%')->get();

        foreach($categories as $category) {
            array_push($data, [
                'id' => $category->id,
                'name' => $category->name,
                'type' => 'category'
                ]
            );
        }

        $courses = Course::where('title', 'like', '%' . $key . '%')->get();

        foreach($courses as $course) {
            array_push($data, [
                'id' => $course->id,
                'name' => $course->title,
                'type' => 'course'
                ]
            );
        }

        $ele = '<ul id="search___result" class="list-unstyled search_result collapse show">';

        $i = 0;

        foreach($data as $item) {
            $i++;
            $ele .= '<li data-id="'. $item['id'] .'" data-type="'. $item['type'] .'">'. $item['name'] .'</li>';
            if($i > 5) {
                break;
            }
        }

        $ele .= '</ul>';

        return response()->json([
            'success' => true,
            'result' => $data,
            'html' => $ele
        ]);
    }

    public function search(Request $request)
    {
        $params = $request->all();
        if(isset($params['_q'])) {
            $courses_me = Course::where('title', 'like', '%' . $params['_q'] . '%')->where('published', 1);
            $categories = Category::where('name', 'like', '%' . $params['_q'] . '%')->get();
            foreach($categories as $category) {
                $courses_c = Course::where('category_id', $category->id)->where('published', 1);
                $courses_me = $courses_me->union($courses_c);
            }
            $courses = $courses_me->paginate(20);
            $courses->setPath('search/courses?_q='. $params['_q']);
        } else {
            $courses = Course::where('published', 1)->paginate('20');
        }
        
        return view('backend.course.student.index', compact('courses'));
    }

    public function searchPage(Request $request)
    {
        $parentCategories = Category::where('parent', 0)->get();
        $params = $request->all();
        
        if(isset($params['_t']) && $params['_t'] == 'category') {
            
            $courses_me = Course::where('category_id', $params['_k']);

            $subCategories = Category::where('parent', $params['_k'])->get();
            foreach($subCategories as $category) {
                $courses_c = Course::where('category_id', $category->id);
                $courses_me = $courses_me->union($courses_c);
            }

            $courses = $courses_me->paginate(20);
            $courses->setPath('search?_q='. $params['_q'] .'&_t='. $params['_t'] .'&_k='. $params['_k']);

        } else {
            
            $courses_me = Course::where('title', 'like', '%' . $params['_q'] . '%');
            $categories = Category::where('name', 'like', '%' . $params['_q'] . '%')->get();
            foreach($categories as $category) {
                $courses_c = Course::where('category_id', $category->id);
                $courses_me = $courses_me->union($courses_c);
            }
            $courses = $courses_me->paginate(20);
            $courses->setPath('search?_q='. $params['_q']);
        }

        return view('frontend.search.index', compact('parentCategories', 'courses'));
    }

    function getCoursesByKey()
    {

    }
}
