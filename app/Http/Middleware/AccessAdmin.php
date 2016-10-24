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

    protected $view_admin = 1;

    public function handle($request, Closure $next)
    {

        $user =  Auth::user();

        
        if($user){//if logged in
            
            if(!$user->access($this->view_admin)){//check if allowed to admin page

                return redirect('/home');

            }
           
        }else{ //if not logged inredirect to home

           return redirect('/');

        }

        return $next($request);
    }
}
