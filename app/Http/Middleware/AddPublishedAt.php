<?php

namespace App\Http\Middleware;

use Closure;

class AddPublishedAt
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
        if($request->has('published')){
            
            $request->merge(['published_at' => \Carbon\Carbon::now()]);
        }
        else{
            
            $request->merge(['published_at' => NULL]);
        }
        
        return $next($request);
       
    }
}
