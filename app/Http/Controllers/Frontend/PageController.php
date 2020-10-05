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
        $page_content = $this->sortCode($page->content);
        $recents = Page::orderBy('created_at', 'desc')->limit(4)->get();
        return view('frontend.page.single', compact('page', 'page_content', 'recents'));
    }

    function sortCode($content)
    {
        $instructor_sign = '<a href=\"\/register?r=t\" class=\"btn btn-primary\">Sign up as Instructor <\/a>';
        return str_replace('[sign_as_instructor]', $instructor_sign, $content);
    }
}
