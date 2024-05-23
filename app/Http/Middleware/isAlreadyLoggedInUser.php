<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
//new line

class isAlreadyLoggedInUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Session()->has('user_id') && (url('userlogin') == $request->url() || url('userregistration') == $request->url()) )
        {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
