<?php

namespace App\Http\Middleware;

use Closure;

class CheckInstructor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->hasRole('Instructor')) {
            if(auth()->user()->profile == 0 && $request->route()->uri != 'dashboard/account') {
                return redirect('dashboard/account')->with('warning', 'Please complete profile');
            }

            if(auth()->user()->profile == 2 && $request->route()->uri != 'dashboard/account') {
                return redirect('dashboard/account')->with('warning', 'Your profile declined, Please submit again');
            }
        }
        return $next($request);
    }
}
