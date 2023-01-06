<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Father
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
        if(Auth::user()->user_type == 'father'){
            return $next($request);
        }elseif(Auth::user()->user_type == 'teacher'){
            return redirect()->route('teacher.home');
        }elseif(Auth::user()->user_type == 'staff'){
            return redirect()->route('admin.home');
        }else{
            Auth::logout();
            return abort(403);
        }
    }
}
