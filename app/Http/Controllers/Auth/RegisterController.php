<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Mail;
use App\Mail\VerifyMail;
use App\Mail\SendMail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'uuid' => Str::uuid()->toString(),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verify_token' => str_random(40)
        ]);

        $user->assignRole($data['role']);

        $data = [
            'template_type' => 'register_verify',
            'mail_data' => $user
        ];

        Mail::to($user->email)->send(new SendMail($data));

        // Mail::to($user->email)->send(new VerifyMail($user));

        return $user;
    }

    public function verifyUser($token)
    {
        $user = User::where('verify_token', $token)->first();
        if(isset($user) ) {
            if(!$user->verified) {
                $user->verified = 1;
                $user->save();
                $status = "Your e-mail is verified. You can now login.";
            } else {
                $status = "Your e-mail is already verified. You can now login.";
            }
        } else {
            return redirect()->route('login')->with('warning', "Sorry your email cannot be identified.");
        }

        return redirect()->route('login')->with('success', $status);
    }

    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
        return view('auth.verify', compact('user'));
        // return redirect()->route('login')->with('success', 'We sent you an activation code. Check your email and click on the link to verify.');
    }

    public function resend(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);

        $data = [
            'template_type' => 'register_verify',
            'mail_data' => $user
        ];

        try {
            Mail::to($user->email)->send(new SendMail($data));
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
