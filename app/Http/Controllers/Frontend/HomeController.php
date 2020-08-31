<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        view()->share('active_nav', 'homepage');
    }

    // Load Homepage
    public function index() {

        // Parent Categories
        $parentCategories = Category::where('parent', 0)->limit(8)->get();

        // Get Featured Courses
        $featuredCourses = Course::where('featured', 1)->limit(8)->get();

        // Top reviews
        $reviews = Review::orderBy('rating', 'desc')->limit(4)->get();

        return view('frontend.index', compact('parentCategories', 'featuredCourses', 'trendingCourses', 'reviews'));
    }
}
