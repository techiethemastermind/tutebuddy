<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CertificateController extends Controller
{
    /**
     * Get certificates lost for purchased courses.
     */
    public function index()
    {
        return view('backend.certificates.index');
    }

    /**
     * Get certificates lost for purchased courses.
     */
    public function getCertificates()
    {
        $certificates = auth()->user()->certificates;
        
        $data = [];
        $i = 0;

        foreach($certificates as $cert) {
            $i++;
            $temp = [];

            $temp['index'] = '<div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                                <label class="custom-control-label"><span class="text-hide">Check</span></label>
                            </div>';
            $temp['no'] = $i;
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . substr($cert->course->title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $cert->course->title . '</strong></small>
                                        <small class="js-lists-values-location text-50">'. $cert->course->teachers[0]->name .'</small>
                                    </div>
                                </div>
                            </div>';

            $temp['progress'] = $cert->course->progress() . '%';
            $btn_view = '<a href="' . asset('storage/certificates/'.$cert->url) . '" class="btn btn-success btn-sm">View</a>';
            $btn_download = '<a href="' . route('admin.certificates.download', ['certificate_id'=>$cert->id]) . 
                '" class="btn btn-primary btn-sm">Download</a>';

            $temp['action'] = $btn_view . '&nbsp;' . $btn_download;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Generate certificate for completed course
     */
    public function generateCertificate(Request $request)
    {
        $course = Course::whereHas('students', function ($query) {
            $query->where('id', \Auth::id());
        })
            ->where('id', '=', $request->course_id)->first();
        if (($course != null) && ($course->progress() == 100)) {
            $certificate = Certificate::firstOrCreate([
                'user_id' => auth()->user()->id,
                'course_id' => $request->course_id
            ]);

            $data = [
                'name' => auth()->user()->name,
                'course_name' => $course->title,
                'date' => Carbon::now()->format('d M, Y'),
            ];
            $certificate_name = 'Certificate-' . $course->id . '-' . auth()->user()->id . '.pdf';
            $certificate->name = auth()->user()->id;
            $certificate->url = $certificate_name;
            $certificate->save();

            $pdf = \PDF::loadView('certificate.index', compact('data'))->setPaper('', 'landscape');

            $pdf->save(public_path('storage/certificates/' . $certificate_name));

            return back()->withFlashSuccess(trans('alerts.frontend.course.completed'));
        }
        return abort(404);
    }

    /**
     * Download certificate for completed course
     */
    public function download(Request $request)
    {
        $certificate = Certificate::findOrFail($request->certificate_id);
        if($certificate != null){
            $file = public_path() . "/storage/certificates/" . $certificate->url;
            return Response::download($file);
        }
        return back()->withFlashDanger('No Certificate found');

    }


    /**
     * Get Verify Certificate form
     */
    public function getVerificationForm()
    {
        return view($this->path.'.certificate-verification');
    }


    /**
     * Verify Certificate
     */
    public function verifyCertificate(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'date' => 'required'
        ]);

        $certificates = Certificate::where('name', '=', $request->name)
            ->where(DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), "=", $request->date)
            ->get();
        $data['certificates'] = $certificates;
        $data['name'] = $request->name;
        $data['date'] = $request->date;
        session()->forget('certificates');
        return back()->with(['data' => $data]);

    }
}