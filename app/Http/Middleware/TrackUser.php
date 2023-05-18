<?php

namespace App\Http\Middleware;

use App\Models\MdVisitor;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackUser
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
        try{
            if ( $request->method() == 'GET' ){
                $visitor = new MdVisitor();
                $visitor->ip = $request->ip();
                $visitor->route = $request->url();
                $visitor->method = $request->method();
                $visitor->save();
            }
        }catch(Exception $e){}
    
        return $next($request);
    }
}
