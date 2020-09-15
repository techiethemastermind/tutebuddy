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
        return view('frontend.user.profile', compact('teacher'));
    }
}
