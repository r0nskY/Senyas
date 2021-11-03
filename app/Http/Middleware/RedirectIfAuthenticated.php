<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {


        if (Auth::guard($guard)->check()) {

         if(\Auth::user()->user_type=="1"){
                return redirect('/home');
            }

          elseif (\Auth::user()->user_type=="0"){
              $ass_mun = \Auth::user()->ass_mun;
                 return redirect("/actioncenter/$ass_mun");
          } elseif (\Auth::user()->user_type=="2"){
                        $ass_mun = \Auth::user()->ass_mun;
                        $dep = \Auth::user()->position;
                           return redirect("/actioncenter/$ass_mun/$dep");
         }
           else{

                 return redirect('/');
           }

        }


        return $next($request);
    }
}
