<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmailTemplate;
use Mail;
use App\Mail\SendMail;
use App\Http\Controllers\Traits\FileUploadTrait;

class EmailtemplateController extends Controller
{
    use FileUploadTrait;
    
    public function index()
    {
        return view('backend.templates.index');
    }

    public function getListByAjax()
    {
        $templates = EmailTemplate::all();
        $data = [];
        foreach($templates as $item) {
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';
            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . substr($item->name, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $item->name . '</strong></small>
                                    </div>
                                </div>
                            </div>';
            
            if($item->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }
            
            $edit_route = route('admin.mailedits.edit', $item->id);
            $delete_route = route('admin.mailedits.destroy', $item->id);

            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }


    /**
     * Create a new discussion
     */
    public function create()
    {
        return view('backend.templates.create');
    }

    /**
     * Store a new discussion
     */
    public function store(Request $request)
    {
        $content = html_entity_decode($request->html_content);

        $data = [
            'name' => $request->name,
            'content' => $content,
            'editor' => $request->editor
        ];

        // logo image
        if(!empty($request->logo)) {
            $image = $request->file('logo');
            $logo_image_url = $this->saveImage($image, 'upload', true);
            $data['logo'] = $logo_image_url;
        }
        
        $template = EmailTemplate::create($data);

        return redirect()->route('admin.mailedits.edit', $template->id);
    }

    public function edit($id)
    {
        $template = EmailTemplate::find($id);
        return view('backend.templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::find($id);
        $content = html_entity_decode($request->html_content);

        $data = [
            'name' => $request->name,
            'content' => $content,
            'editor' => $request->editor
        ];

        // logo image
        if(!empty($request->logo)) {
            $image = $request->file('logo');
            $logo_image_url = $this->saveImage($image, 'upload', true);
            $data['logo'] = $logo_image_url;
        }

        $template->update($data);

        return response()->json([
            'success' => true
        ]);

    }

    public function sendTestEmail(Request $request)
    {
        $template = EmailTemplate::find($request->id);
        $data = [
            'email' => $request->email,
            'template' => $template
        ];

        try {
            Mail::to($data['email'])->send(new SendMail($data));

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
