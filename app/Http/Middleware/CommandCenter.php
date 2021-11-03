<?php

namespace App\Http\Middleware;

use Closure;


class CommandCenter
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
        if ( \Auth::check() && \Auth::user()->user_type == "1" )
        {

            return $next($request);
        }
        if (\Auth::user()->user_type=="1"){
            return redirect("/home");
        }

        return redirect('/home');
    }
}
