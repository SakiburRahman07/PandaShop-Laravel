<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAlreadyLoggedinVendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Session()->has('vendor_id') && (url('vendorlogin') == $request->url() || url('vendorregistration') == $request->url()) )
        {
            return redirect()->route('vendor.dashboard');
        }
        return $next($request);    }
}
