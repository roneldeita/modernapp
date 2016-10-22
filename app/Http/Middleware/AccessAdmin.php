<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AccessAdmin
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

        $user =  Auth::user();

        //if logged in
        if($user){
            
            //check if allowed to admin page
            if(!$user->access(1)){

                return redirect()->intended('/home');

            }
            //if not redirect to home
        }else{

           return redirect('/');

        }
        

        return $next($request);
    }
}
