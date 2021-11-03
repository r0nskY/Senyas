<?php

namespace App\Http\Middleware;

use Closure;

class ActionCenter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $group = $request->assmun;
        $ass_mun = \Auth::user()->ass_mun;

        if ( \Auth::check() && \Auth::user()->user_type == "0" && \Auth::user()->municipality == $group or \Auth::user()->user_type=="2")
        {
            return $next($request);
        }

            return redirect("/actioncenter/$ass_mun");
    }
}
