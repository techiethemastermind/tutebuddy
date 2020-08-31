<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return back()->with('error', 'Wrong login information added.');
    }

    public function authenticated(Request $request, $user)
    {
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
            if (!$user->verified) {
                auth()->logout();
                return back()->with('warning', 'You need to confirm your account. We have sent you an activation code, please check your email.');
            }
    
            if (!$user->active) {
                auth()->logout();
                return back()->with('warning', 'Your account has been disabled by admin. please contact to support.');
            }
        } else {
            auth()->logout();
            return back()->withErrors(['captcha' => 'ReCaptcha Error']);
        }

        return redirect()->intended($this->redirectTo);
    }
}
