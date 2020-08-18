<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Course;
use App\Models\Review;

class ReviewController extends Controller
{
    //
    public function index() {
        // list
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
}
