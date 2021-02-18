<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Course;
use App\Models\Review;
use App\Models\Bundle;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // $featuredCourses = Course::where('featured', 1)->limit(8)->get();

        $featuredCourses = Course::where('end_date', '>=', Carbon::now()->format('Y-m-d'))->orderBy('created_at', 'desc')->limit(8)->get();

        // Top reviews
        $reviews = Review::where('rating', '5')->orderBy('created_at', 'desc')->limit(4)->get();

        // Top Paths
        // $bundles = Bundle::where('published', 1)->limit(6)->get();

        $bundles = Bundle::orderBy('created_at', 'desc')->limit(6)->get();

        return view('frontend.index', compact('parentCategories', 'featuredCourses', 'reviews', 'bundles'));
    }
}
