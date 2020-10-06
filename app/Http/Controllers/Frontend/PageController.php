<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

use Mail;
use App\Mail\SendMail;

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
        $contact_form = '<div class=\"card card-body\">
                            <form id=\"contact\" action=\"/ajax/email/contact\">
                                <div class=\"form-group\">
                                    <label class=\"form-label\">Full Name *:<\/label>
                                    <input name=\"name\" type=\"text\" class=\"form-control\" placeholder=\"Your first and last name ...\" required>
                                <\/div>
                                <div class=\"form-group\">
                                    <label class=\"form-label\">Company *:<\/label>
                                    <input name=\"company\" type=\"text\" class=\"form-control\" placeholder=\"Your Company Name ...\" required>
                                <\/div>
                                <div class=\"form-group\">
                                    <label class=\"form-label\">Company Email *:<\/label>
                                    <input name=\"company_email\" type=\"email\" class=\"form-control\" placeholder=\"Your Company email address ...\" required>
                                <\/div>
                                <div class=\"row\">
                                    <div class=\"form-group col-8\">
                                        <label class=\"form-label\">Business Phone Number *:<\/label>
                                        <input name=\"business_phone\" type=\"text\" class=\"form-control\" placeholder=\"Business phone number ...\" required>
                                    <\/div>
                                    <div class=\"form-group col-4\">
                                        <label class=\"form-label\">Ext:<\/label>
                                        <input name=\"ext\" type=\"text\" class=\"form-control\" placeholder=\"Ext ...\">
                                    <\/div>
                                <\/div>
                                <div class=\"form-group\">
                                    <label class=\"form-label\">Mobile Number *:<\/label>
                                    <input name=\"mobile_phone\" type=\"text\" class=\"form-control\" placeholder=\"Mobile phone number ...\" required>
                                <\/div>
                                <div class=\"form-group\">
                                    <label class=\"form-label\">Best time to reach you:<\/label>
                                    <input name=\"meet_time\" type=\"text\" class=\"form-control\" placeholder=\"Best time ...\">
                                <\/div>
                                <div class=\"form-group \">
                                    <label class=\"form-label\">Message *:<\/label>
                                    <textarea name=\"message\" class=\"form-control\" rows=\"4\" placeholder=\"Message here ...\" required></textarea>
                                <\/div>
                                <div class=\"form-group form-inline mb-24pt\">
                                    <div class=\"custom-control custom-radio\">
                                        <input id=\"by_email\" name=\"contact_type\" type=\"radio\" value=\"By Email\" class=\"custom-control-input\" checked=\"\">
                                        <label for=\"by_email\" class=\"custom-control-label\">By Email</label>
                                    </div>
                                    <div class=\"custom-control custom-radio ml-3\">
                                        <input id=\"by_mobile_phone\" name=\"contact_type\" type=\"radio\" value=\"By Mobile Phone\" class=\"custom-control-input\" >
                                        <label for=\"by_mobile_phone\" class=\"custom-control-label\">Call me on Mobile</label>
                                    </div>
                                    <div class=\"custom-control custom-radio ml-3\">
                                        <input id=\"by_business_phone\" name=\"contact_type\" type=\"radio\" value=\"By Business Phone\" class=\"custom-control-input\">
                                        <label for=\"by_business_phone\" class=\"custom-control-label\">Call me on Business Phone</label>
                                    </div>
                                </div>
                                <button class=\"btn btn-primary\">Submit<\/button>
                            <\/form>
                        <\/div>';

        $html = str_replace('[sign_as_instructor]', $instructor_sign, $content);
        $html = str_replace('[contact_form]', $contact_form, $html);

        return $html;
    }

    public function sendContactEmail(Request $request)
    {
        $inputs = $request->all();

        $data = [
            'template_type' => 'contact',
            'mail_data' => $inputs
        ];

        try {
            Mail::to($inputs['company_email'])->send(new SendMail($data));
            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
