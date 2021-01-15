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
