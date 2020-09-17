<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Course;
use App\User;

class SearchController extends Controller
{
    // Search Course
    public function courses(Request $request)
    {
        $params = $request->all();
        $parentCategories = Category::where('parent', 0)->get();

        if(isset($params['_t']) && $params['_t'] == 'category') {
            
            $courses_me = Course::where('category_id', $params['_k']);

            $subCategories = Category::where('parent', $params['_k'])->get();
            foreach($subCategories as $category) {
                $courses_c = Course::where('category_id', $category->id);
                $courses_me = $courses_me->union($courses_c);
            }

            $courses = $courses_me->paginate(10);
            $courses->setPath('search?_q='. $params['_q'] .'&_t='. $params['_t'] .'&_k='. $params['_k']);

        } else {
            
            if(isset($params['_q'])) {
                $courses_me = Course::where('title', 'like', '%' . $params['_q'] . '%')->where('published', 1);
                $categories = Category::where('name', 'like', '%' . $params['_q'] . '%')->get();
                foreach($categories as $category) {
                    $courses_c = Course::where('category_id', $category->id)->where('published', 1);
                    $courses_me = $courses_me->union($courses_c);
                }
                $courses = $courses_me->paginate(10);
                $courses->setPath('search/courses?_q='. $params['_q']);
            } else {
                $courses = Course::where('published', 1)->paginate('10');
            }
        }
        
        return view('frontend.search.courses', compact('parentCategories', 'courses'));
    }

    // Search instructor
    public function teachers(Request $request)
    {
        $params = $request->all();

        if(isset($params['_q'])) {
            $teachers = User::role('Instructor')
                ->where('name', 'like', '%' . $params['_q'] . '%')
                ->orWhere('headline', 'like', '%' . $params['_q'] . '%')->paginate(10);
        } else {
            $teachers = User::role('Instructor')->orderBy('created_at', 'desc')->paginate(10);
        }
        
        return view('frontend.search.teachers', compact('teachers'));
    }

    public function getSearchFormCourseData($key)
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

    public function getSearchFormUserData($key)
    {
        $data = [];
        $users = User::where('name', 'like', '%' . $key . '%')->get();
        foreach($users as $user) {
            array_push($data, [
                'id' => $user->id,
                'name' => $user->name,
                'type' => 'user'
                ]
            );
        }

        $subjects = User::where('headline', 'like', '%' . $key . '%')->get();
        foreach($subjects as $user) {
            array_push($data, [
                'id' => $user->id,
                'name' => $user->headline,
                'type' => 'headline'
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
}
