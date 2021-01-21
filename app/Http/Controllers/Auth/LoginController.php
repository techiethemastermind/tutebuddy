<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\AccessHistory;
use Stevebauman\Location\Facades\Location;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    // /**
    // * Get the login username to be used by the controller.
    // *
    // * @return string
    // */
    // public function username()
    // {
    //     $login = request()->input('identity');

    //     $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    //     request()->merge([$field => $login]);

    //     return $field;
    // }

    // /**
    //  * Validate the user login request.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return void
    //  *
    //  * @throws \Illuminate\Validation\ValidationException
    //  */
    // protected function validateLogin(Request $request)
    // {
    //     $messages = [
    //         'identity.required' => 'Email or username cannot be empty',
    //         'email.exists' => 'Email or username already registered',
    //         'username.exists' => 'Username is already registered',
    //         'password.required' => 'Password cannot be empty',
    //     ];

    //     $request->validate([
    //         'identity' => 'required|string',
    //         'password' => 'required|string',
    //         'email' => 'string|exists:users',
    //         'username' => 'string|exists:users',
    //     ], $messages);
    // }

    public function login(Request $request)
    {   
        $input = $request->all();
  
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
  
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password'])))
        {
            return redirect()->intended($this->redirectTo);
        }else{
            return redirect()->route('login')
                ->with('error','Email-Address And Password Are Wrong.');
        }
          
    }

    public function authenticated(Request $request, $user)
    {
        if($request->role == 'user' && $user->hasRole('Administrator')) {
            auth()->logout();
            return back()->with('warning', 'Wrong Credentials added!');
        }

        if($request->role == 'admin' && !$user->hasRole('Administrator')) {
            auth()->logout();
            return back()->with('warning', 'Wrong Credentials added!');
        }

        if (!$user->verified) {
            auth()->logout();
            return back()->with('warning', 'We have sent you an activation code. \n please check your email.');
        }

        if (!$user->active) {
            auth()->logout();
            return back()->with('warning', 'Your account has been disabled by admin. \n please contact to support.');
        }

        if(config("access.captcha.registration") > 0) {
            // Recaptcha
            $vars = array(
                'secret' => config('captcha.secret'),
                "response" => $request->input('recaptcha_v3')
            );
            $url = "https://www.google.com/recaptcha/api/siteverify";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
            $encoded_response = curl_exec($ch);
            $response = json_decode($encoded_response, true);
            curl_close($ch);

            if($response['success'] && $response['action'] == 'login' && $response['score']>0.5) {
                return redirect()->intended($this->redirectTo);
            } else {
                auth()->logout();
                return back()->withErrors(['captcha' => 'ReCaptcha Error']);
            }
        }

        $user->update([
            'last_login_at' => \Carbon\Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);

        $position = Location::get($request->getClientIp());
        if($position) {
            $location = $position->cityName . ', ' . $position->countryName;
        } else {
            $location = 'Loopback';
        }

        // AccessHistory::create([
        //     'user_id' => $user->id,
        //     'user_name' => $user->name,
        //     'user_email' => $user->email,
        //     'role' => $user->getRoleNames()->first(),
        //     'logined_at' => \Carbon\Carbon::now()->toDateTimeString(),
        //     'logined_ip' => $request->getClientIp(),
        //     'logined_location' => $location
        // ]);

        return redirect()->intended($this->redirectTo);
    }
}
