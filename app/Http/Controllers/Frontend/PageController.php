<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class PageController extends Controller
{
    public function getPage($slug)
    {
        $page = Page::where('slug', $slug)->first();
        $recents = Page::orderBy('created_at', 'desc')->limit(5)->get();
        return view('frontend.page.single', compact('page', 'recents'));
    }
}
