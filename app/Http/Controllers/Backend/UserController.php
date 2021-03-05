<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Course;
use App\Models\Bank;
use App\Models\AccessHistory;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use FileUploadTrait;
    use SoftDeletes;

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
        $count = [
            'admins' => User::role('Administrator')->count(),
            'teachers' => User::role('Instructor')->count(),
            'students' => User::role('Student')->count()
        ];

        return view('backend.users.index', compact('count'));

        // $data = User::orderBy('id','ASC')->paginate(10);
        // return view('backend.users.index',compact('data'))
        //     ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function getList($type)
    {
        switch ($type) {
            case 'admins':
                $users = User::role('Administrator')->get();
            break;
            case 'teachers':
                $users = User::role('Instructor')->get();
            break;
            case 'students':
                $users = User::role('Student')->get();
            break;
            default:
                $users = User::role('Student')->get();
        }

        $count = [
            'admins' => User::role('Administrator')->count(),
            'teachers' => User::role('Instructor')->count(),
            'students' => User::role('Student')->count()
        ];

        $data = [];
        foreach($users as $user) {
            $temp = [];
            $temp['index'] = '';

            if(!empty($user->avatar)) {
                $avatar = '<img src="'. asset('/storage/avatars/' . $user->avatar ) .'" alt="Avatar" class="avatar-img rounded-circle">';
            } else {
                $avatar = '<span class="avatar-title rounded-circle">'. substr($user->name, 0, 2) . '</span>';
            }

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    '. $avatar .'
                                </div>
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">'. $user->name .'</strong></p>
                                            <small class="js-lists-values-email text-50">'.
                                                $user->getRoleNames()->first()
                                            .'</small>
                                        </div>
                                    </div>
                                </div>
                            </div>';

            $temp['email'] = $user->email;

            if($user->verified) {
                $temp['status'] = '<label class="badge badge-primary" data-toggle="tooltip" data-original-title="Verified">Good</label>';
            } else {
                $temp['status'] = '<label class="badge badge-warning" data-toggle="tooltip" data-original-title="Not verified">No</label>';
            }

            if($user->getRoleNames()->first() == 'Instructor') {
                if($user->profile == 1) {
                    $temp['status'] .= '<label class="badge badge-success ml-4pt" data-toggle="tooltip" data-original-title="Profile Approved">Approve</label>';
                }

                if($user->profile == 2) {
                    $temp['status'] .= '<label class="badge badge-yellow ml-4pt" data-toggle="tooltip" data-original-title="Profile is Pending">Pending</label>';
                }
            }

            $temp['roles'] = '';
            if(!empty($user->getRoleNames())) {
                foreach($user->getRoleNames() as $r) {
                    $temp['roles'] .= '<label class="badge badge-primary">'. $r .'</label>';
                }
            }

            $temp['group'] = $user->roles->pluck('type')[0];

            $btn_show = view('backend.buttons.show', ['show_route' => route('admin.users.show', $user->id)])->render();
            $btn_edit = view('backend.buttons.edit', ['edit_route' => route('admin.users.edit', $user->id)])->render();

            // $btn_delete = view('backend.buttons.delete', ['delete_route' => route('admin.users.destroy', $user->id)])->render();
            // $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;

            $temp['actions'] = $btn_show . '&nbsp;' . $btn_edit;

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $count
        ]);
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
    
        // Check User email unique
        $user_exist = User::where('email', $input['email'])->count();
        if($user_exist > 0) {
            return response()->json([
                'success' => false,
                'message' => 'The email already used'
            ]);
        } else {
            $user = User::create($input);

            $avatar = $request->has('avatar') ? $request->file('avatar') : false;
            if($avatar) {
                $avatar_url = $this->saveImage($avatar, 'avatar');
                $user->avatar = $avatar_url;
                $user->save();
            }
            
            $user->assignRole($request->input('roles'));
        
            return response()->json([
                'success' => true,
                'message' => 'New User created Successfully'
            ]);
        }
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
        try {

            User::find($id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function myAccount()
    {
        $user = auth()->user();
        return view('backend.users.account', compact('user'));
    }

    /**
     * Create Child Account
     */
    public function childAccount(Request $request) {

        $data = $request->all();

        // Check name is duplicated or not
        $child_ids = DB::table('user_child')->where('user_id', auth()->user()->id)->pluck('child_id');
        $child_names = User::whereIn('id', $child_ids)->pluck('name')->toArray();

        if(in_array($data['name'], $child_names)) {
            return response()->json([
                'success' => false,
                'message' => 'Name is duplicated',
                'action' => 'child'
            ]);
        }
        
        // Generate Unique User Name
        $user_name = $this->get_username($data['name']);
        $message = 'Successfully Created';

        try {
            $user = User::create([
                'uuid' => Str::uuid()->toString(),
                'name' => $data['name'],
                'email' => $user_name . '@tutebuddy.com',
                'username' => $user_name,
                'nick_name' => $data['nick_name'],
                'password' => Hash::make($data['password']),
                'verify_token' => str_random(40),
                'country' => auth()->user()->country,
                'state' => auth()->user()->state,
                'city' => auth()->user()->city,
                'address' => auth()->user()->address,
                'zip' => auth()->user()->zip,
                'timezone' => auth()->user()->timezone
            ]);

            DB::table('user_child')->insert([
                'user_id' => auth()->user()->id,
                'child_id' => $user->id
            ]);
        } catch (Exception $e) {

            $message = $e->getMessage();
        }

        $user->assignRole('Child');

        return response()->json([
            'success' => true,
            'message' => $message,
            'action' => 'child'
        ]);
    }

    /**
     * Update child account
     */
    public function childAccountUpdate(Request $request) {
        $data = array_filter($request->all());
        $update_data = [
            'name' => $data['name'],
            'nick_name' => $data['nick_name']
        ];

        if(!empty($data['password'])) { 
            $update_data['password'] = Hash::make($data['password']);
        }

        $child = User::where('id', $data['child_id'])->update($update_data);

        if($child) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong, Please try again!'
            ]);
        }
    }

    private function get_username($name) {
        $slug = str_slug($name);
    
        if ($this->usernameExist($slug)) {
        	$name = $name . '_1';
            return $this->get_username($name);
        }
    
        // otherwise, it's valid and can be used
        return $slug;
    }
    
    private function usernameExist($slug) {
        return empty(User::where('username', $slug)->first()) ? false : true;
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
                'success' => true,
                'message' => 'Successfully Updated'
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

        if($user->hasRole('Instructor')) {
            if($user->profile == 0 || $user->profile == 2) {
                $user->profile = 3;
                $user->save();
            }
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
                                    </div>
                                </div>
                            </div>';
            $temp['course'] = '<strong>'. $student['course']->title .'</strong>';
            $temp['start_date'] = '<strong>'. $student['course']->start_date .'</strong>';
            $temp['end_date'] = '<strong>'. $student['course']->end_date .'</strong>';

            if($student['course']->progress($student['user']) > 99) {
                $status = '<span class="indicator-line rounded bg-success"></span>';
            } else {
                $status = '<span class="indicator-line rounded bg-primary"></span>';
            }
            $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">'. $student['course']->progress($student['user']) .'%</small>
                                    '. $status .'
                                </div>';

            $temp['actions'] = '<a href="'. route('admin.results.studentDetail', [$student['user']->id, $student['course']->id]) .'" class="btn-accent btn-sm">Detail</a>';
            $temp['actions'] .= '<a href="#" class="btn-primary btn-sm ml-2">Certificate</a>';

            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get Access History
     */
    public function getHistory()
    {
        return view('backend.users.access-history');
    }

    /**
     * Get History Table Data
     */
    public function getHistoryByAjax(Request $request)
    {
        $offset = $request->start;
        $limit = $request->length;
        $history_obj = AccessHistory::orderBy('logined_at', 'desc')->offset($offset)->limit($limit);
        $q = $request->search['value'];
        if(!empty($q)) {
            $histories = $history_obj->where('user_name', 'like', '%' . $q . '%')
                ->orWhere('user_email', 'like', '%' . $q . '%')
                ->orWhere('logined_location', 'like', '%' . $q . '%')
                ->orWhere('role', 'like', '%' . $q . '%')
                ->get();
        } else {
            $histories = $history_obj->get();
        }
        $data = [];
        foreach($histories as $history) {
            $temp['index'] = '';
            $temp['name'] = $history->user_name;
            $temp['email'] = $history->user_email;
            $temp['role'] = $history->user->getRoleNames()->first();
            $temp['access_time'] = $history->logined_at;
            $temp['access_ip'] = $history->logined_ip;
            $temp['location'] = $history->logined_location;
            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Approve Account
     */
    public function approveAccount($id)
    {
        $user = User::find($id);
        $user->profile = 1;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User profile Approved'
        ]);
    }

    /**
     * Decline Account
     */
    public function declineAccount($id)
    {
        $user = User::find($id);
        $user->profile = 2;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User profile declined'
        ]);
    }
}
