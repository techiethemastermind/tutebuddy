<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function getTeacherProfile($uuid)
    {
        $teacher = User::where('uuid', $uuid)->first();
        $similar_teachers = User::role('Instructor')->where('id', '!=', $teacher->id)->orderBy('created_at', 'desc')->limit(4)->get();
        return view('frontend.user.profile', compact('teacher', 'similar_teachers'));
    }
}
