<?php

namespace App\Http\Middleware;

use App\Main\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isMod
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
        if (Auth::user() && ( Auth::user()->role === Utils::PHO_QUAN_LY || Auth::user()->role === Utils::QUAN_LY) ){
            return $next($request);
        }
        return redirect()->route('dashboard');
    }
}
