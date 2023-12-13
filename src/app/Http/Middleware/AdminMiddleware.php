<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (Auth::users()->role == '1') {
                return $next($request);
            } else {
                return redirect('/product')->with('message', 'Access Denied as you are not a Admin!');
            }
        } else {
            return redirect('/login')->with('message', 'Login to access the website info');

        }
    }
}
