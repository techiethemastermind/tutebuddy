<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use App\Http\Controllers\Traits\FileUploadTrait;

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
        $data = User::orderBy('id','ASC')->paginate(5);
        return view('backend.users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input,array('password'));    
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
    
        return redirect()->route('admin.users.index')
                        ->with('success','User updated successfully');
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
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';

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
}
