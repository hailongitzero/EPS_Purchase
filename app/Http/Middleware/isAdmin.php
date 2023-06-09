<?php

namespace App\Http\Middleware;

use App\Main\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->role != Utils::QUAN_LY){
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
