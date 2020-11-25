<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Course;
use App\Models\Bank;

class UserController extends Controller
{
    use FileUploadTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','ASC')->paginate(10);
        return view('backend.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('backend.users.create', compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);

        $avatar = $request->has('avatar') ? $request->file('avatar') : false;
        if($avatar) {
            $avatar_url = $this->saveImage($avatar, 'avatar');
            $user->avatar = $avatar_url;
            $user->save();
        }
        
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('admin.users.index')
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('backend.users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('backend.users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {    
        $input = $request->all();

        if(!empty($input['password'])) { 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));    
        }

        if(!isset($input['active'])) {
            $input['active'] = 0;
        }

        $user = User::find($id);
        $user->update($input);

        $avatar = $request->has('avatar') ? $request->file('avatar') : false;
        if($avatar) {
            $avatar_url = $this->saveImage($avatar, 'avatar');
            $user->avatar = $avatar_url;
            $user->save();
        }

        DB::table('model_has_roles')->where('model_id', $id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return back()->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('admin.users.index')
                        ->with('success','User deleted successfully');
    }

    public function myAccount()
    {
        $user = auth()->user();
        $child = $user->child();
        return view('backend.users.account', compact('user', 'child'));
    }

    public function updateAccount(Request $request, $id)
    {
        $input = $request->all();

        $user = User::find($id);

        if(isset($input['update_type']) && $input['update_type'] == 'password') {

            $hashedPassword = $user->password;

            if (Hash::check($input['current_password'], $hashedPassword)) {
                if($input['new_password'] == $input['confirm_password']) {
                    $input['password'] = Hash::make($input['new_password']);
                } else {
                    return response()->json([
                        'success' => false,
                        'action' => 'update',
                        'message' => 'Please confirm password'
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'action' => 'update',
                    'message' => 'Incorrect current password provided'
                ]);
            }
        }

        if(isset($input['update_type']) && $input['update_type'] == 'bank') {

            $bank = Bank::where('user_id', auth()->user()->id)->first();
            $curl_headers = [
                'Content-Type: application/json',
                'Authorization: Basic '. base64_encode(config('services.razorpayX.key') . ':' . config('services.razorpay.secret'))
            ];

            $contact_id = '';
            $fund_account_id = '';

            if(empty($bank)) {

                // Create Contact
                $params = [
                    'name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                    'contact' => auth()->user()->phone_number,
                    'type' => 'employee',
                    'reference_id' => 'contact_' . str_random(8)
                ];

                $options = [
                    CURLOPT_URL => 'https://api.razorpay.com/v1/contacts',
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($params),
                    CURLOPT_HTTPHEADER => $curl_headers,
                    CURLOPT_RETURNTRANSFER => 1
                ];

                $ch = curl_init();
                curl_setopt_array($ch, $options);

                $response = curl_exec($ch);
                $result = json_decode($response, true);
                $contact_id = $result['id'];
                curl_close($ch);

                // Create fund Account
                $params = [
                    'contact_id' => $contact_id,
                    'account_type' => 'bank_account',
                    'bank_account' => [
                        'name' => $request->account_holder_name,
                        'ifsc' => $request->ifsc,
                        'account_number' => $request->account_number
                    ]
                ];

                $options = [
                    CURLOPT_URL => 'https://api.razorpay.com/v1/fund_accounts',
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($params),
                    CURLOPT_HTTPHEADER => $curl_headers,
                    CURLOPT_RETURNTRANSFER => 1
                ];

                $ch = curl_init();
                curl_setopt_array($ch, $options);

                $response = curl_exec($ch);
                $result = json_decode($response, true);
                $fund_account_id = $result['id'];
                curl_close($ch);

                $bank_data = [
                    'user_id' => auth()->user()->id,
                    'account_number' => $request->account_number,
                    'ifsc' => $request->ifsc,
                    'account_holder_name' => $request->account_holder_name,
                    'account_type' => 'employee',
                    'contact_id' => $contact_id,
                    'fund_account_id' => $fund_account_id
                ];

                Bank::updateOrCreate(['user_id' => auth()->user()->id], $bank_data);

            } else {
                $bank_data = [
                    'user_id' => auth()->user()->id,
                    'account_number' => $request->account_number,
                    'ifsc' => $request->ifsc,
                    'account_holder_name' => $request->account_holder_name,
                    'account_type' => 'employee'
                ];

                Bank::updateOrCreate(['user_id' => auth()->user()->id], $bank_data);
            }

            return response()->json([
                'success' => true
            ]);
        }
        
        $user->update($input);

        $avatar = $request->has('avatar') ? $request->file('avatar') : false;
        if($avatar) {
            $avatar_url = $this->saveImage($avatar, 'avatar');
            $user->avatar = $avatar_url;
            $user->save();
        }

        if(isset($input['categories'])) {
            $user->profession = json_encode($input['categories']);
            $user->save();
        }

        if(isset($input['qualification'])) {
            $user->qualifications = json_encode($input['qualification']);
            $user->save();
        }

        if(isset($input['achievement'])) {
            $user->achievements = json_encode($input['achievement']);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'action' => 'update'
        ]);
    }

    public function search(Request $request)
    {
        $params = $request->all();
    }

    public function studentInstructors()
    {
        return view('backend.users.student');
    }

    public function getStudentInstructorsByAjax()
    {
        $course_ids = DB::table('course_student')->where('user_id', auth()->user()->id)->pluck('course_id');
        $teacher_ids = DB::table('course_user')->whereIn('course_id', $course_ids)->pluck('user_id');
        $teachers = User::whereIn('id', $teacher_ids)->get();

        $data = [];
        foreach($teachers as $teacher) {
            $temp = [];
            $temp['index'] = '';

            if(empty($teacher->avatar)) {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($teacher->name, 0, 2) .'</span>';
            } else {
                $avatar = '<img src="'. asset('/storage/avatars/' . $teacher->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">'. $avatar .'</div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $teacher->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $teacher->headline .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['email'] = '<strong>' . $teacher->email . '</strong>';

            $btn_follow = '<a href="#" target="_blank" class="btn btn-primary btn-sm">Follow</a>';
            $btn_show = '<a href="'. route('profile.show', $teacher->uuid) .'" class="btn btn-accent btn-sm">View Profile</a>';

            $temp['action'] = $btn_follow . '&nbsp;' . $btn_show;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function enrolledStudents()
    {
        return view('backend.users.teacher');
    }

    public function getEnrolledStudentsByAjax()
    {
        $course_ids = DB::table('course_user')->where('user_id', auth()->user()->id)->pluck('course_id');
        $course_students = DB::table('course_student')->whereIn('course_id', $course_ids)->get();

        $students = collect();
        foreach($course_students as $item) {
            $c_item = Course::find($item->course_id);
            $u_item = User::find($item->user_id);
            $data = [
                'course' => $c_item,
                'user' => $u_item
            ];
            $students->push($data);
        }

        $data = [];
        foreach($students as $student) {
            $temp = [];
            $temp['index'] = '';
            
            if(!empty($student['user']->avatar)) {
                $avatar = '<img src="'. asset('/storage/avatars/' . $student['user']->avatar) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($student['user']->name, 0, 2) .'</span>';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    '. $avatar .'
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-name">'. $student['user']->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'. $student['user']->email .'</small>
                                        </div>
                                        <div class="d-flex align-items-center ml-24pt">
                                            <i class="material-icons text-20 icon-16pt">comment</i>
                                            <small class="ml-4pt"><strong class="text-50">1</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>';
            $temp['course'] = '<strong>'. $student['course']->title .'</strong>';
            $temp['start_date'] = '<strong>'. $student['course']->start_date .'</strong>';
            $temp['end_date'] = '<strong>'. $student['course']->end_date .'</strong>';

            if($student['course']->progress() > 99) {
                $status = '<span class="indicator-line rounded bg-success"></span>';
            } else {
                $status = '<span class="indicator-line rounded bg-primary"></span>';
            }
            $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. $student['course']->progress() .'%</small>
                                    '. $status .'
                                </div>';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
